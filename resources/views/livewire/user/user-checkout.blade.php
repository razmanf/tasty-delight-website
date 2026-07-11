@section('title', 'Checkout')

<div class="max-w-4xl mx-auto pb-10">
    <h1 class="text-2xl font-bold mb-6" style="color: var(--td-text);">
        <i class="fa-solid fa-credit-card mr-2" style="color: var(--td-primary);"></i> Checkout
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Order Summary & Details -->
        <div class="space-y-6">
            <div class="td-card">
                <h2 class="font-bold text-lg mb-4" style="color: var(--td-text);">Delivery Details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Address</label>
                        <input type="text" wire:model="address" class="td-search-input w-full" placeholder="123 Tasty Street" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Phone</label>
                        <input type="text" wire:model="phone" class="td-search-input w-full" placeholder="+1 234 567 8900" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Notes</label>
                        <textarea wire:model="notes" class="td-search-input w-full h-20" placeholder="Extra spicy, please..."></textarea>
                    </div>
                </div>
            </div>

            <div class="td-card">
                <h2 class="font-bold text-lg mb-4" style="color: var(--td-text);">Order Summary</h2>
                <div class="space-y-3">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <span style="color: var(--td-text);">{{ $item->quantity }}x {{ $item->product->name }}</span>
                            <span class="font-medium" style="color: var(--td-text);">$ {{ number_format($item->quantity * $item->product->price, 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t pt-3 mt-3 flex justify-between items-center font-bold text-lg" style="border-color: var(--td-border);">
                        <span style="color: var(--td-text);">Total to Pay</span>
                        <span style="color: var(--td-primary);">$ {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Element -->
        <div class="td-card h-fit" wire:ignore>
            <h2 class="font-bold text-lg mb-4" style="color: var(--td-text);">Payment Method</h2>
            
            @if($clientSecret === 'simulated_test_secret')
                <!-- Simulated Portfolio Test Mode UI -->
                <div class="p-4 mb-6 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center space-y-3" style="border-color: var(--td-primary); background: #DD66250A;">
                    <div class="flex gap-2 text-2xl text-gray-400">
                        <i class="fa-brands fa-cc-visa text-blue-600"></i>
                        <i class="fa-brands fa-cc-mastercard text-red-500"></i>
                        <i class="fa-brands fa-cc-amex text-blue-400"></i>
                    </div>
                    <div>
                        <span class="font-bold block" style="color: var(--td-text);">Portfolio Test Mode Active</span>
                        <span class="text-xs" style="color: var(--td-muted);">No real credit card required. Just click pay to simulate a transaction.</span>
                    </div>
                </div>
            @else
                <!-- Stripe Elements -->
                <div id="payment-element" class="mb-6"></div>
            @endif

            <div id="payment-message" class="hidden text-red-500 text-sm mb-4 font-medium"></div>

            <button id="submit" class="td-btn-primary w-full justify-center py-3 text-base flex items-center gap-2 relative">
                <span id="button-text"><i class="fa-solid fa-lock"></i> Pay $ {{ number_format($total, 2) }}</span>
                <span id="spinner" class="hidden absolute inset-0 flex items-center justify-center bg-[#DD6625] rounded-xl"><i class="fa-solid fa-circle-notch fa-spin text-white"></i></span>
            </button>
        </div>
    </div>

    <!-- Payment Logic -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const formButton = document.getElementById('submit');
            const clientSecret = '{{ $clientSecret }}';

            if (clientSecret === 'simulated_test_secret') {
                // Handle Simulated Portfolio Mode
                formButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelector('#button-text').classList.add('hidden');
                    document.querySelector('#spinner').classList.remove('hidden');
                    formButton.disabled = true;

                    // Simulate network request delay
                    setTimeout(() => {
                        @this.processOrder();
                    }, 1500);
                });
            } else {
                // Handle Real Stripe Mode
                const stripeScript = document.createElement('script');
                stripeScript.src = "https://js.stripe.com/v3/";
                document.head.appendChild(stripeScript);
                
                stripeScript.onload = () => {
                    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
                    const appearance = {
                        theme: document.documentElement.classList.contains('dark') ? 'night' : 'stripe',
                        variables: { colorPrimary: '#DD6625' }
                    };
                    const options = { clientSecret: clientSecret, appearance: appearance };
                    const elements = stripe.elements(options);
                    const paymentElement = elements.create('payment', { layout: 'tabs' });
                    paymentElement.mount('#payment-element');

                    formButton.addEventListener('click', async (e) => {
                        e.preventDefault();
                        document.querySelector('#button-text').classList.add('hidden');
                        document.querySelector('#spinner').classList.remove('hidden');
                        formButton.disabled = true;

                        const { error } = await stripe.confirmPayment({
                            elements,
                            confirmParams: {},
                            redirect: 'if_required' 
                        });

                        if (error) {
                            const msg = document.querySelector('#payment-message');
                            msg.classList.remove('hidden');
                            msg.textContent = error.message;
                            document.querySelector('#button-text').classList.remove('hidden');
                            document.querySelector('#spinner').classList.add('hidden');
                            formButton.disabled = false;
                        } else {
                            @this.processOrder();
                        }
                    });
                };
            }
        });
    </script>
</div>
