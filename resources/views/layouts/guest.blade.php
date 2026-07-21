<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title') @yield('title') @else Login or register - TastyDelight @endif</title>

        <!-- Open Graph / WhatsApp Link Previews -->
        <meta property="og:title" content="@hasSection('title') @yield('title') @else TastyDelight - Premium Food Delivery @endif" />
        <meta property="og:description" content="Experience the best food in town. Fast delivery, fresh ingredients, and mouth-watering meals delivered straight to your door." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:image" content="{{ asset('images/tasty-delight-logo.png') }}" />
        
        <!-- Twitter / iMessage Large Image -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="@hasSection('title') @yield('title') @else TastyDelight - Premium Food Delivery @endif" />
        <meta name="twitter:description" content="Experience the best food in town. Fast delivery, fresh ingredients, and mouth-watering meals delivered straight to your door." />
        <meta name="twitter:image" content="{{ asset('images/tasty-delight-logo.png') }}" />

        <!-- FavIcon -->
        <link rel="icon" type="image/svg+xml" href="/storage/favicons/favicon.svg" />
        <link rel="shortcut icon" href="/storage/favicons/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/storage/favicons/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="TastyDelight" />
        <link rel="manifest" href="/storage/favicons/site.webmanifest" />

        <!-- Preload Important Assets -->
        <link rel="preload" as="image" href="{{ asset('images/background.svg') }}" type="image/svg+xml" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="bg-no-repeat bg-[length:700px] max-[470px]:bg-[length:150vw] sm:bg-[length:800px] min-[1200px]:bg-[length:950px] bg-[position:center_0rem] min-[1200px]:bg-[position:calc(50vw-750px)_center] min-[1440px]:bg-[length:1200px] min-[1440px]:bg-[position:calc(50vw-920px)_center]" style="background-image: url('{{ asset('images/background.svg') }}');">
        <div class="font-sans text-gray-900 antialiased bg-transparent">
            {{ $slot }}
        </div>

        @livewireScripts

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const validateField = (field) => {
                    let errorMsg = '';
                    const value = field.value.trim();
                    const name = field.name;

                    if (field.hasAttribute('required') && !value) {
                        if (name === 'role') {
                            errorMsg = 'Please select a role';
                        } else {
                            const niceName = name.replace('_', ' ');
                            errorMsg = `The ${niceName} field is required.`;
                        }
                    } else if (value) {
                        if (name === 'contact_number') {
                            if (!/^0[0-9]{9}$/.test(value)) {
                                errorMsg = 'Enter a 10 digit contact number';
                            }
                        } else if (name === 'email') {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(value)) {
                                errorMsg = 'The email field must be a valid email address.';
                            }
                        } else if ((name === 'password' || name === 'password_confirmation') && value.length < 8) {
                            errorMsg = 'The password must be 8 characters or more';
                        }
                        
                        if (name === 'password_confirmation' && value) {
                            const pwdField = document.querySelector('[name="password"]');
                            if (pwdField && pwdField.value !== value) {
                                errorMsg = 'The passwords do not match';
                            }
                        }
                    }

                    // Handle inputs inside flex wrappers (like contact number + button)
                    let parent = field.parentNode.classList.contains('flex') ? field.parentNode.parentNode : field.parentNode;
                    
                    // Remove existing error smoothly if it exists
                    let errorContainer = parent.querySelector('.live-validation-error');
                    if (errorContainer && !errorMsg) {
                        errorContainer.classList.remove('opacity-100', 'max-h-10');
                        errorContainer.classList.add('opacity-0', 'max-h-0');
                        setTimeout(() => errorContainer.remove(), 300);
                    } else if (errorContainer && errorMsg) {
                        errorContainer.textContent = errorMsg;
                    } else if (errorMsg) {
                        errorContainer = document.createElement('p');
                        errorContainer.className = 'text-red-600 text-xs mt-1 live-validation-error transition-all duration-300 ease-in-out opacity-0 max-h-0 overflow-hidden';
                        errorContainer.textContent = errorMsg;
                        parent.appendChild(errorContainer);
                        
                        // force reflow
                        void errorContainer.offsetWidth;
                        
                        errorContainer.classList.remove('opacity-0', 'max-h-0');
                        errorContainer.classList.add('opacity-100', 'max-h-10');
                    }
                };

                // Attach to all inputs inside forms
                document.querySelectorAll('form input, form select').forEach(field => {
                    field.addEventListener('blur', (e) => {
                        // ignore hidden checkboxes or logic fields like remember_me
                        if(e.target.type !== 'checkbox' && e.target.type !== 'hidden') {
                            validateField(e.target);
                        }
                    });
                });
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', (e) => {
                        let hasErrors = false;
                        form.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"]), select').forEach(field => {
                            validateField(field);
                            if (field.classList.contains('border-red-500')) {
                                hasErrors = true;
                            }
                        });
                        if (hasErrors) {
                            e.preventDefault();
                        }
                    });
                });
            });
        </script>
    </body>
</html>
