<x-guest-layout>
    @section('title', 'Register - TastyDelight')
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
                    <div x-data="{
                            open: false,
                            selected: '{{ old('role') }}',
                            options: [
                                { value: '', label: 'Select a role...' },
                                { value: 'user', label: 'User' },
                                { value: 'admin', label: 'Admin' }
                            ],
                            get selectedLabel() {
                                return this.options.find(opt => opt.value === this.selected)?.label || 'Select a role...';
                            }
                        }"
                         class="relative mt-1"
                         @click.outside="open = false">
                         
                        <select name="role" id="role" class="hidden" x-model="selected" required>
                            <option value=""></option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <!-- Trigger Button -->
                        <button type="button"
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-[#DD6625] focus:ring-[#DD6625] sm:text-sm bg-white text-gray-700 transition-colors">
                            <span x-text="selectedLabel" :class="selected === '' ? 'text-gray-500' : ''"></span>
                            <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                             style="display: none;">
                            <template x-for="option in options" :key="option.value">
                                <button type="button"
                                        @click="selected = option.value; open = false"
                                        class="w-full text-left px-4 py-2 hover:bg-[#DD6625]/10 hover:text-[#DD6625] transition-colors flex items-center justify-between text-gray-900"
                                        :class="selected === option.value ? 'bg-[#DD6625]/10 text-[#DD6625] font-medium' : ''">
                                    <span x-text="option.label"></span>
                                    <svg x-show="selected === option.value" class="h-5 w-5 text-[#DD6625]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>

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
