<x-guest-layout>

        <div class="max-w-md ml-auto mr-28">
            <x-authentication-card>
                <x-slot name="logo"></x-slot>
    
                <x-validation-errors class="mb-4" />

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
                    </div>

                    <div class="mt-4">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="block mt-4">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
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
                        <button class="bg-[#dd6625] hover:bg-orange-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                            VISIT SITE
                        </button>
                    </a>
                </div>
            </x-authentication-card>
        </div>


</x-guest-layout>
