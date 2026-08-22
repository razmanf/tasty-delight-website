<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service - TastyDelight</title>
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
                <h1 class="text-4xl md:text-6xl font-black mb-4"><span class="gradient-text">Terms</span> of Service</h1>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto font-medium">Last updated: {{ date('F j, Y') }}</p>
            </div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-6 py-16 max-w-4xl">
            <div class="td-card p-8 md:p-12 space-y-8 text-gray-600 dark:text-gray-300 leading-relaxed">
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Agreement to Terms</h2>
                    <p>By accessing or using our services, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any part of these terms, you may not use our services. TastyDelight reserves the right to modify these terms at any time.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Account Responsibilities</h2>
                    <p>When you create an account with us, you must provide accurate, complete, and current information. You are responsible for safeguarding your password and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Ordering and Payment</h2>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>All orders are subject to availability and acceptance by TastyDelight.</li>
                        <li>Prices are subject to change without notice. The total cost of your order will be displayed before checkout.</li>
                        <li>We use third-party payment processors (e.g., Stripe) to handle transactions securely. We do not store your full credit card information.</li>
                        <li>Refunds or cancellations are handled in accordance with our cancellation policy.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Intellectual Property</h2>
                    <p>The Service and its original content, features, and functionality are and will remain the exclusive property of TastyDelight and its licensors. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of TastyDelight.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Limitation of Liability</h2>
                    <p>In no event shall TastyDelight, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the Service.</p>
                </section>

                <div class="pt-8 mt-8 border-t border-gray-100 dark:border-gray-800 text-center">
                    <p>If you have any questions about these Terms, please contact us at <a href="mailto:support@tastydelight.shop" class="text-orange-500 font-bold hover:underline">support@tastydelight.shop</a>.</p>
                    <a href="/" class="td-btn-primary mt-8 inline-block">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
