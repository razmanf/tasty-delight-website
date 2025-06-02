namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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
}