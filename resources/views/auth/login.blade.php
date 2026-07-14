<x-guest-layout>
    @section('title', 'Login - TastyDelight')

        <div class="w-full min-[1200px]:w-[50vw] min-[1200px]:ml-auto min-[1200px]:mr-0 px-4 sm:px-0">
            <x-authentication-card>
                <x-slot name="logo"></x-slot>
    

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <div class="text-center text-gray-600 mb-4">
                    <h1 class="text-2xl font-bold">Login</h1>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        @error('email')
                            <p class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        @error('password')
                            <p class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="block mt-3">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="block mt-3">
                        <label for="show_password" class="flex items-center">
                            <x-checkbox id="show_password" name="show_password" />
                            <span class="ms-2 text-sm text-gray-600">{{ __('Show password') }}</span>
                        </label>
                    </div>    

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-button class="ms-4">
                            {{ __('Log in') }}
                        </x-button>
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


                        </div>
                    @endif

                </form>



                <script>
                    document.getElementById('show_password').addEventListener('change', function () {
                        const password = document.getElementById('password');
                        const passwordConfirm = document.getElementById('password_confirmation');
                        const type = this.checked ? 'text' : 'password';
                
                        password.type = type;
                        if (passwordConfirm) passwordConfirm.type = type;
                    });
                </script>

            </x-authentication-card>
        </div>


</x-guest-layout>
