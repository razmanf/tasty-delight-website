<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact_number' => ['required', 'string', 'size:10', 'regex:/^0[0-9]{9}$/', 'unique:users'],
            'password' => $this->passwordRules(),
            'role'     => ['required', 'in:user,admin'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'email.unique' => 'This email address has already been taken',
            'contact_number.size' => 'Enter a 10 digit contact number',
            'contact_number.regex' => 'Enter a 10 digit contact number',
            'password.min' => 'The password must be 8 characters or more',
            'password.confirmed' => 'The passwords do not match',
            'role.required' => 'Please select a role',
            'role.in' => 'Please select a role',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'contact_number' => $input['contact_number'],
            'password' => Hash::make($input['password']),
            'role'     => in_array($input['role'], ['user', 'admin'])
                          ? $input['role']
                          : 'user',
        ]);
    }
}
