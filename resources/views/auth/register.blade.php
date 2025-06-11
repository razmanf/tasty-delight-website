<x-guest-layout>
    <div class="max-w-md ml-auto mr-28">
        <x-authentication-card>
            <x-slot name="logo"></x-slot>

            <x-validation-errors class="mb-4" />

            <div class="text-center text-gray-600 mb-4">
                <h1 class="text-2xl font-bold">Register</h1>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="block mt-4">
                    <x-label for="show_password">
                        <div class="flex items-center">
                            <x-checkbox name="show_password" id="show_password" />
                            
                            <div class="ms-2">
                                {{ __('Show Password') }}
                            </div>
                        </div>
                    </x-label>
                </div>                

                <div class="mt-4">
                    <x-label for="role" value="{{ __('Registering as') }}" />
                    <select
                        id="role"
                        name="role"
                        required
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                    >
                        {{-- truly blank option --}}
                        <option value="" {{ old('role') === '' ? 'selected' : '' }}></option>

                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                            {{ __('User') }}
                        </option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                            {{ __('Admin') }}
                        </option>
                    </select>

                    @error('role')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>



                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required />

                                <div class="ms-2">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                        @error('terms')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-button class="ms-4">
                        {{ __('Register') }}
                    </x-button>
                </div>

                {{-- OR Divider --}}
                <div class="flex items-center my-4">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="mx-3 text-gray-500 text-base">or</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>
            </form>

            {{-- Visit Site Button --}}
            <div class="text-center mt-4">
                <a href="{{ url('/') }}">
                    <button class="bg-[#dd6625] hover:bg-orange-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                        VISIT SITE
                    </button>
                </a>
            </div>

            <script>
                document.getElementById('show_password').addEventListener('change', function () {
                    const password = document.getElementById('password');
                    const passwordConfirm = document.getElementById('password_confirmation');
                    const type = this.checked ? 'text' : 'password';
            
                    password.type = type;
                    passwordConfirm.type = type;
                });
            </script>
            
        </x-authentication-card>
</x-guest-layout>
