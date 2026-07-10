<x-guest-layout>
    @section('title', 'Reset password - TastyDelight')
    <div class="max-w-md ml-auto mr-28">
        <x-authentication-card>
            <x-slot name="logo"></x-slot>

        <x-validation-errors class="mb-4" />

        <div class="text-center text-gray-600 mb-4">
            <h1 class="text-2xl font-bold">Reset Password</h1>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
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
                <label for="show_password" class="flex items-center">
                    <x-checkbox id="show_password" name="show_password" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Show password') }}</span>
                </label>
            </div>    

            <div class="flex items-center justify-end mt-8 pb-4">
                <div class="flex-1">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← Back to Login
                    </a>
                </div>
                
                <div>
                    <x-button>
                        {{ __('Reset Password') }}
                    </x-button>
                </div>
            </div>
            
            @if (Route::has('register'))
                <div class="w-full mt-6">
                    <div class="border-t border-gray-300 w-full"></div>
                    <p class="text-sm text-center text-black mt-5 p-2">
                        Need an account?
                        <a href="{{ route('register') }}" class="underline text-gray-600 hover:text-gray-900">
                            {{ __('Register') }}
                        </a>
                    </p>

                    {{-- OR Divider --}}
                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="mx-3 text-gray-500 text-base">or</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>
                </div>
            @endif
        </form>

        {{-- Visit Site Button --}}
        <div class="text-center mt-4">
            <a href="{{ url('/') }}">
                <button type="button" class="bg-[#dd6625] hover:bg-orange-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
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
                if(passwordConfirm) passwordConfirm.type = type;
            });
        </script>
        </x-authentication-card>
    </div>
</x-guest-layout>
