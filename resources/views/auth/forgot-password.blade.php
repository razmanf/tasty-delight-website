<x-guest-layout>
    @section('title', 'Forgot password - TastyDelight')
    <div class="w-full min-[1200px]:w-[50vw] min-[1200px]:ml-auto min-[1200px]:mr-0 px-4 sm:px-0">
        <x-authentication-card>
            <x-slot name="logo"></x-slot>

            <div class="text-center text-gray-600 mb-4">
                <h1 class="text-2xl font-bold">Forgot Password</h1>
            </div>

            <div class="mb-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession


            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="block">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    @error('email')
                        <p class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-8 pb-4">

                        <div class="flex-1">
                            <a href="{{ route('login') }}"
                            class="text-sm text-gray-600 hover:text-gray-900">
                                ← Back to Login
                            </a>
                        </div>

                        <div>
                            <x-button>
                                {{ __('Email Password Reset Link') }}
                            </x-button>
                        </div>

                </div>
            </form>
        </x-authentication-card>
</x-guest-layout>
