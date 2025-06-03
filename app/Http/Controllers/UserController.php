<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserController extends Controller
{   
    // Remove this: use AuthorizesRequests;

    // Get authenticated user's profile
    public function profile(Request $request)
    {
        return new UserResource($request->user()->load('orders', 'reviews'));
    }

    // List all users - remove authorization
    public function index()
    {
        // $this->authorize('viewAny', User::class);  <-- Remove this line
        return UserResource::collection(User::with('orders')->paginate(10));
    }

    // Create user - remove authorization
    public function store(Request $request)
    {
        // $this->authorize('create', User::class);  <-- Remove this line
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'sometimes|in:admin,customer'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    // User registration stays same (no authorization needed here)
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

        return (new UserResource($user))->response()->setStatusCode(201);

    }

    // Update profile - remove authorization
    public function update(Request $request, User $user)
    {
        // $this->authorize('update', $user);  <-- Remove this line
        
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

    // Delete user - remove authorization
    public function destroy(User $user)
    {
        // $this->authorize('delete', $user);  <-- Remove this line
        
        $user->delete();
        return response()->noContent();
    }
}
