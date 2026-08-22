<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy - TastyDelight</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background: var(--td-bg); color: var(--td-text); }
        .gradient-text { background: linear-gradient(135deg, var(--td-primary) 0%, var(--td-warning) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen">
    <div class="flex-grow">
        <!-- Header -->
        <div class="relative py-20 bg-black/5 overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-10" style="background-image: radial-gradient(var(--td-primary) 1px, transparent 1px); background-size: 30px 30px;"></div>
            <div class="container mx-auto px-6 relative z-10 text-center">
                <a href="/" class="inline-block mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="TastyDelight Logo" class="h-16 mx-auto hover:scale-105 transition-transform" onerror="this.outerHTML='<i class=\'fa-solid fa-burger text-5xl gradient-text\'></i>'">
                </a>
                <h1 class="text-4xl md:text-6xl font-black mb-4"><span class="gradient-text">Privacy</span> Policy</h1>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto font-medium">Last updated: {{ date('F j, Y') }}</p>
            </div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-6 py-16 max-w-4xl">
            <div class="td-card p-8 md:p-12 space-y-8 text-gray-600 dark:text-gray-300 leading-relaxed">
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Information We Collect</h2>
                    <p>We collect information you provide directly to us, such as when you create or modify your account, request on-demand services, contact customer support, or otherwise communicate with us. This information may include: name, email, phone number, postal address, profile picture, payment method, and other information you choose to provide.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. How We Use Your Information</h2>
                    <p>We may use the information we collect about you to:</p>
                    <ul class="list-disc pl-5 space-y-2 mt-2">
                        <li>Provide, maintain, and improve our services, including to process transactions and send related information.</li>
                        <li>Send you technical notices, updates, security alerts, and support messages.</li>
                        <li>Respond to your comments, questions, and requests, and provide customer service.</li>
                        <li>Communicate with you about products, services, offers, and events offered by TastyDelight.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Sharing of Information</h2>
                    <p>We may share the information we collect about you as described in this Statement or as described at the time of collection or sharing, including as follows:</p>
                    <ul class="list-disc pl-5 space-y-2 mt-2">
                        <li>With third-party payment processors to complete your transactions.</li>
                        <li>In response to a request for information by a competent authority if we believe disclosure is in accordance with, or is otherwise required by, any applicable law, regulation, or legal process.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Security</h2>
                    <p>We take reasonable measures to help protect information about you from loss, theft, misuse, and unauthorized access, disclosure, alteration, and destruction. However, no internet or email transmission is ever fully secure or error-free.</p>
                </section>

                <div class="pt-8 mt-8 border-t border-gray-100 dark:border-gray-800 text-center">
                    <p>If you have any questions about this Privacy Policy, please contact us at <a href="mailto:privacy@tastydelight.shop" class="text-orange-500 font-bold hover:underline">privacy@tastydelight.shop</a>.</p>
                    <a href="/" class="td-btn-primary mt-8 inline-block">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
