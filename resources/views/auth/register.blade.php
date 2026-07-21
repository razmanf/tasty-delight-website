<x-guest-layout>
    @section('title', 'Register - TastyDelight')
    <div class="w-full min-[1200px]:w-[50vw] min-[1200px]:ml-auto min-[1200px]:mr-0 px-4 sm:px-0">
        <x-authentication-card>
            <x-slot name="logo"></x-slot>



            <div class="text-center text-gray-600 mb-4">
                <h1 class="text-2xl font-bold">Register</h1>
            </div>

            <form method="POST" action="{{ route('register') }}" x-data="otpVerification()">
                @csrf

                <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                    <!-- Row 1 -->
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        @error('name')
                            <p class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" @input="emailError = ''" />
                        <div x-show="emailError" style="display: none;">
                            <p x-text="emailError" class="text-red-600 text-xs mt-1"></p>
                        </div>
                        @error('email')
                            <p x-show="!emailError" class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Row 2 -->
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                        @error('password')
                            <p class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                        @error('password_confirmation')
                            <p class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Show Password -->
                    <div class="col-span-2 block mt-1">
                        <x-label for="show_password">
                            <div class="flex items-center">
                                <x-checkbox name="show_password" id="show_password" />
                                <div class="ms-2 text-sm text-gray-600">
                                    {{ __('Show Password') }}
                                </div>
                            </div>
                        </x-label>
                    </div>

                    <!-- Row 3 -->
                    <div class="col-span-2 sm:col-span-1">
                        <x-label for="contact_number" value="{{ __('Contact Number') }}" />
                        <div class="flex gap-2 mt-1 w-full">
                            <x-input id="contact_number" x-model="contactNumber" @input="backendError = ''" class="block w-full" type="text" name="contact_number" required placeholder="0xxxxxxxxx" maxlength="10" />
                            <button type="button" @click="sendOtp()" :disabled="isLoading || !isContactValid" :class="{'opacity-50 cursor-not-allowed pointer-events-none': isLoading || !isContactValid, 'hover:bg-gray-300': !isLoading && isContactValid}" class="px-3 py-2 bg-gray-200 text-gray-700 font-bold rounded-md transition-colors text-sm flex-shrink-0 min-w-[100px]">
                                <span x-show="!isLoading">Send OTP</span>
                                <span x-show="isLoading" style="display: none;">Sending...</span>
                            </button>
                        </div>
                    </div>

                    <!-- OTP Code Input (Hidden until OTP is sent) -->
                    <div class="col-span-2 sm:col-span-1" x-show="otpSent" style="display: none;">
                        <x-label for="otp_code" value="{{ __('6-Digit OTP Code') }}" />
                        <x-input id="otp_code" class="block mt-1 w-full text-center tracking-widest font-bold" type="text" name="otp_code" maxlength="6" placeholder="------" />
                        <p class="text-xs text-green-600 mt-1 font-semibold">OTP sent! Please check your email inbox.</p>
                        @error('otp_code')
                            <p class="text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-100 max-h-10 overflow-hidden">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number Error Message Row -->
                    <div class="col-span-2">
                        <div x-show="backendError" style="display: none;">
                            <p x-text="backendError" class="text-red-600 text-xs"></p>
                        </div>
                        @error('contact_number')
                            <div>
                                <p x-show="!backendError" class="text-red-600 text-xs live-validation-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
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

                    <x-button class="ms-4" x-bind:disabled="!otpSent && !{{ $errors->has('otp_code') ? 'true' : 'false' }}" x-bind:class="{ 'opacity-50 cursor-not-allowed pointer-events-none': !otpSent && !{{ $errors->has('otp_code') ? 'true' : 'false' }} }">
                        {{ __('Register') }}
                    </x-button>
                </div>


            </form>



            <script>
                document.getElementById('show_password').addEventListener('change', function () {
                    const password = document.getElementById('password');
                    const passwordConfirm = document.getElementById('password_confirmation');
                    const type = this.checked ? 'text' : 'password';
            
                    password.type = type;
                    passwordConfirm.type = type;
                });

                function otpVerification() {
                    return {
                        contactNumber: '{{ old('contact_number') }}',
                        backendError: '',
                        emailError: '',
                        // Keep open if there was an OTP validation error previously
                        otpSent: {{ old('otp_code') || $errors->has('otp_code') ? 'true' : 'false' }},
                        isLoading: false,
                        get isContactValid() {
                            return /^0[0-9]{9}$/.test(this.contactNumber);
                        },
                        sendOtp() {
                            const emailField = document.getElementById('email').value;
                            if (!this.isContactValid || !emailField) {
                                this.backendError = 'Please enter a valid email and contact number first.';
                                return;
                            }
                            this.isLoading = true;
                            this.backendError = '';
                            this.emailError = '';
                            fetch('{{ route('registration.otp.send') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ contact_number: this.contactNumber, email: emailField })
                            })
                            .then(async res => {
                                this.isLoading = false;
                                if (res.ok) {
                                    this.otpSent = true;
                                } else {
                                    const data = await res.json();
                                    if (data.errors) {
                                        if (data.errors.email) {
                                            console.log("Setting emailError to:", data.errors.email[0]);
                                            this.emailError = data.errors.email[0];
                                        }
                                        if (data.errors.contact_number) {
                                            this.backendError = data.errors.contact_number[0];
                                        }
                                        if (!data.errors.email && !data.errors.contact_number) {
                                            const firstErrorKey = Object.keys(data.errors)[0];
                                            this.backendError = data.errors[firstErrorKey][0];
                                        }
                                    } else {
                                        this.backendError = data.message || 'Error sending OTP. Please try again.';
                                    }
                                }
                            })
                            .catch(() => {
                                this.isLoading = false;
                                this.backendError = 'A network error occurred while sending the OTP.';
                            });
                        }
                    }
                }
            </script>
            
        </x-authentication-card>
</x-guest-layout>
