namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Get authenticated user's profile
    public function profile(Request $request)
    {
        return new UserResource($request->user()->load('orders', 'reviews'));
    }

    // Admin-only: List all users
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return UserResource::collection(User::with('orders')->paginate(10));
    }

    // Admin-only: Create user
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'sometimes|in:admin,customer'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        return new UserResource($user, 201);
    }

    // Public: User registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'role' => 'customer'
        ]);

        return new UserResource($user, 201);
    }

    // Update profile (authenticated users can update their own)
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'current_password' => ['required_with:password', 'current_password']
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return new UserResource($user->fresh());
    }

    // Admin-only: Delete user
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->noContent();
    }

    // Email Verification
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully']);
    }

    // Resend Verification Email
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link resent']);
    }

    // Password Reset Request
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['error' => __($status)], 400);
    }

    // Password Reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['error' => __($status)], 400);
    }

    // Admin: Impersonation
    public function impersonate(Request $request, User $user)
    {
        $this->authorize('impersonate', User::class);
        
        $token = $user->createToken('impersonation-token', [
            'abilities' => ['user']
        ])->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    // Admin: Bulk Actions
    public function bulkActions(Request $request)
    {
        $this->authorize('bulkActions', User::class);

        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        $users = User::whereIn('id', $request->ids)->get();

        switch ($request->action) {
            case 'activate':
                $users->each->update(['active' => true]);
                break;
            case 'deactivate':
                $users->each->update(['active' => false]);
                break;
            case 'delete':
                $users->each->delete();
                break;
        }

        return response()->json(['message' => 'Bulk action completed']);
    }
}