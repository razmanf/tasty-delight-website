@extends('layouts.user')

@section('title', 'Contact Us')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-black mb-4" style="font-family: 'Outfit', sans-serif; color: var(--td-text);">Get In Touch</h1>
            <p class="text-lg" style="color: var(--td-muted);">We'd love to hear from you. Drop us a line below.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border" style="border-color: var(--td-border);">
            
            <!-- Contact Info -->
            <div class="p-8 md:p-12 text-white flex flex-col justify-between" style="background: linear-gradient(135deg, var(--td-primary), #B3521E);">
                <div>
                    <h2 class="text-3xl font-bold mb-6">Contact Information</h2>
                    <p class="mb-10 text-white/90 leading-relaxed">Whether you have questions about your order, our menu, or catering services, our team is ready to answer all your questions.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <i class="fa-solid fa-location-dot mt-1 text-xl text-white/80"></i>
                            <div>
                                <h4 class="font-bold">Our Location</h4>
                                <p class="text-white/80">123 Culinary Boulevard<br>Flavor City, FC 90210</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fa-solid fa-phone mt-1 text-xl text-white/80"></i>
                            <div>
                                <h4 class="font-bold">Phone Number</h4>
                                <p class="text-white/80">+1 (555) 123-4567</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fa-solid fa-envelope mt-1 text-xl text-white/80"></i>
                            <div>
                                <h4 class="font-bold">Email Address</h4>
                                <p class="text-white/80">support@tastydelight.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-colors">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-colors">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-colors">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="p-8 md:p-12">
                <h2 class="text-2xl font-bold mb-6" style="color: var(--td-text);">Send a Message</h2>
                <form action="#" method="POST" class="space-y-6" onsubmit="event.preventDefault(); alert('Thank you! Your message has been sent.');">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">First Name</label>
                            <input type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:border-transparent transition-all" style="color: var(--td-text); focus-ring-color: var(--td-primary);" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Last Name</label>
                            <input type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:border-transparent transition-all" style="color: var(--td-text); focus-ring-color: var(--td-primary);" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Email Address</label>
                        <input type="email" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:border-transparent transition-all" style="color: var(--td-text); focus-ring-color: var(--td-primary);" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Subject</label>
                        <input type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:border-transparent transition-all" style="color: var(--td-text); focus-ring-color: var(--td-primary);" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--td-text);">Message</label>
                        <textarea rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:border-transparent transition-all" style="color: var(--td-text); focus-ring-color: var(--td-primary);" required></textarea>
                    </div>

                    <button type="submit" class="td-btn-primary w-full justify-center py-3">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
