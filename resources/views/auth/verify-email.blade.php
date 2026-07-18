<x-guest-layout>
    @section('title', 'Verify Email - TastyDelight')

    <div class="w-full min-[1200px]:w-[50vw] min-[1200px]:ml-auto min-[1200px]:mr-0 px-4 sm:px-0">
        <x-authentication-card>
            <x-slot name="logo"></x-slot>

            <div class="text-center text-gray-600 mb-6">
                <h1 class="text-2xl font-bold">Verify Email</h1>
            </div>

            <div class="mb-4 text-sm text-gray-600 leading-relaxed">
                {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md">
                    {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
                </div>
            @endif

            <div class="mt-6 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <x-button type="submit" class="bg-gray-800 hover:bg-gray-700">
                            {{ __('Resend Verification Email') }}
                        </x-button>
                    </div>
                </form>

                <div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf

                        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </x-authentication-card>
    </div>
</x-guest-layout>
