@section('title', 'Checkout')

<div class="max-w-4xl mx-auto pb-10" x-data="checkoutState()">
    <h1 class="text-2xl font-bold mb-6" style="color: var(--td-text);">
        <i class="fa-solid fa-credit-card mr-2" style="color: var(--td-primary);"></i> Checkout
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- STEP 1 & 2 CONTAINER -->
        <div class="space-y-6">
            
            <!-- STEP 1: Fulfillment -->
            @if($step === 1)
                <div class="td-card">
                    <h2 class="font-bold text-lg mb-4" style="color: var(--td-text);">1. Fulfillment Method</h2>
                    
                    <!-- Toggle Delivery/Pickup -->
                    <div class="flex rounded-full border mb-6 overflow-hidden" style="border-color: var(--td-border); background: var(--td-bg);">
                        <button wire:click="$set('order_type', 'delivery')" class="flex-1 py-2 text-sm font-bold transition-all"
                            style="{{ $order_type === 'delivery' ? 'background: var(--td-primary); color: white;' : 'color: var(--td-text);' }}">
                            <i class="fa-solid fa-person-biking mr-1.5"></i> Delivery
                        </button>
                        <button wire:click="$set('order_type', 'pickup')" class="flex-1 py-2 text-sm font-bold transition-all"
                            style="{{ $order_type === 'pickup' ? 'background: var(--td-primary); color: white;' : 'color: var(--td-text);' }}">
                            <i class="fa-solid fa-store mr-1.5"></i> Pickup
                        </button>
                    </div>

                    <!-- Map Container -->
                    <div class="rounded-xl overflow-hidden border relative mb-4" style="border-color: var(--td-border); height: 250px;" wire:ignore>
                        <div id="checkout-map" class="w-full h-full z-10"></div>
                        @if($order_type === 'delivery')
                        <button @click="locateMe()" type="button" class="absolute bottom-4 right-4 bg-white text-black p-2 rounded-full shadow-lg z-[1000] hover:bg-gray-100" title="Find My Location">
                            <i class="fa-solid fa-location-crosshairs text-lg"></i>
                        </button>
                        @endif
                    </div>

                    <!-- Delivery Address Input (If Delivery) -->
                    @if($order_type === 'delivery')
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Delivery Address</label>
                        <input type="text" wire:model.blur="delivery_address" class="td-search-input w-full" placeholder="Enter delivery address (Sri Lanka)" required>
                        @error('delivery_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Date and Time -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Date</label>
                            @if($order_type === 'delivery')
                                <input type="date" wire:model.blur="delivery_date" min="{{ date('Y-m-d') }}" class="td-search-input w-full" required>
                            @else
                                <input type="date" wire:model.blur="pickup_date" min="{{ date('Y-m-d') }}" class="td-search-input w-full" required>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Time</label>
                            @if($order_type === 'delivery')
                                <select wire:model.blur="delivery_time" class="td-search-input w-full" required>
                                    <option value="asap">ASAP (30-45 min)</option>
                                    <option value="12pm">12:00 PM</option>
                                    <option value="1pm">1:00 PM</option>
                                    <option value="6pm">6:00 PM</option>
                                    <option value="7pm">7:00 PM</option>
                                </select>
                            @else
                                <select wire:model.blur="pickup_time" class="td-search-input w-full" required>
                                    <option value="asap">ASAP (15-20 min)</option>
                                    <option value="12pm">12:00 PM</option>
                                    <option value="1pm">1:00 PM</option>
                                    <option value="6pm">6:00 PM</option>
                                    <option value="7pm">7:00 PM</option>
                                </select>
                            @endif
                        </div>
                    </div>

                    <!-- Store Details (Always visible) -->
                    <div class="p-4 rounded-xl border mt-4" style="border-color: var(--td-border); background: var(--td-bg);">
                        <h4 class="font-bold text-sm mb-2" style="color: var(--td-text);">Store Information</h4>
                        <p class="text-sm" style="color: var(--td-muted);"><i class="fa-solid fa-map-pin w-5 text-center"></i> 273, Katugastota Road, Kandy, Sri Lanka 20800</p>
                        <p class="text-sm mt-1" style="color: var(--td-muted);"><i class="fa-solid fa-phone w-5 text-center"></i> +94 77 123 4567</p>
                        <p class="text-sm mt-1" style="color: var(--td-muted);"><i class="fa-solid fa-envelope w-5 text-center"></i> hello@tastydelight.lk</p>
                    </div>

                    <!-- Payment Method (Delivery Only) -->
                    @if($order_type === 'delivery')
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-2" style="color: var(--td-text);">Payment Method</label>
                        <select wire:model.blur="payment_method" class="td-search-input w-full">
                            <option value="cash">💵 Cash on Delivery</option>
                            <option value="card">💳 Pay by Card</option>
                        </select>
                    </div>
                    @endif

                    <div class="mt-6">
                        <button wire:click="goToReview" class="td-btn-primary w-full py-3 justify-center">Continue to Review</button>
                    </div>
                </div>
            @endif

            <!-- STEP 2: Review & Notes -->
            @if($step === 2 || $step === 3)
                <div class="td-card">
                    <h2 class="font-bold text-lg mb-4 flex items-center justify-between" style="color: var(--td-text);">
                        <span>2. Order Review</span>
                        @if($step === 2)
                            <button wire:click="goBack" class="text-sm text-blue-500 hover:underline">Edit Details</button>
                        @endif
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Preparation Note (Optional)</label>
                            <textarea wire:model.blur="preparation_note" class="td-search-input w-full h-20" placeholder="E.g., Extra spicy, no onions..."></textarea>
                        </div>
                        @if($order_type === 'delivery')
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Delivery Note (Optional)</label>
                            <textarea wire:model.blur="delivery_note" class="td-search-input w-full h-20" placeholder="E.g., Leave at the door..."></textarea>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- RIGHT COLUMN: ORDER SUMMARY & STRIPE -->
        <div class="space-y-6">
            <div class="td-card h-fit">
                <h2 class="font-bold text-lg mb-4" style="color: var(--td-text);">Order Summary</h2>
                <div class="space-y-3 mb-4">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <span style="color: var(--td-text);">{{ $item->quantity }}x {{ $item->product->name }}</span>
                            <span class="font-medium" style="color: var(--td-text);">$ {{ number_format($item->quantity * $item->product->price, 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t pt-3 mt-3 flex justify-between items-center font-bold text-lg" style="border-color: var(--td-border);">
                        <span style="color: var(--td-text);">Total</span>
                        <span style="color: var(--td-primary);">$ {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @if($step === 2)
                    <button wire:click="confirmOrder" class="td-btn-primary w-full py-3 justify-center">
                        @if($order_type === 'pickup' || ($order_type === 'delivery' && $payment_method === 'cash'))
                            <i class="fa-solid fa-check mr-2"></i> Place Order Now
                        @else
                            <i class="fa-solid fa-lock mr-2"></i> Proceed to Payment
                        @endif
                    </button>
                @endif
                
                @if($step === 3 && $order_type === 'delivery' && $payment_method === 'card')
                    <div class="mt-6 border-t pt-6" style="border-color: var(--td-border);" wire:ignore>
                        <h3 class="font-bold mb-4" style="color: var(--td-text);">Card Details</h3>
                        @if($clientSecret === 'simulated_test_secret')
                            <div class="p-4 mb-6 rounded-xl border-2 border-dashed flex flex-col items-center justify-center text-center space-y-3" style="border-color: var(--td-primary); background: #DD66250A;">
                                <div class="flex gap-2 text-2xl text-gray-400">
                                    <i class="fa-brands fa-cc-visa text-blue-600"></i>
                                    <i class="fa-brands fa-cc-mastercard text-red-500"></i>
                                    <i class="fa-brands fa-cc-amex text-blue-400"></i>
                                </div>
                                <div>
                                    <span class="font-bold block" style="color: var(--td-text);">Portfolio Test Mode Active</span>
                                    <span class="text-xs" style="color: var(--td-muted);">No real credit card required.</span>
                                </div>
                            </div>
                        @else
                            <div id="payment-element" class="mb-4"></div>
                        @endif
                        <div id="payment-message" class="hidden text-red-500 text-sm mb-4 font-medium"></div>
                        <button id="submit" class="td-btn-primary w-full py-3 justify-center flex items-center relative">
                            <span id="button-text">Pay $ {{ number_format($total, 2) }}</span>
                            <span id="spinner" class="hidden absolute inset-0 flex items-center justify-center bg-[#DD6625] rounded-xl"><i class="fa-solid fa-circle-notch fa-spin text-white"></i></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Map & Payment Logic -->
    <script>
        function checkoutState() {
            return {
                map: null,
                storeMarker: null,
                deliveryMarker: null,
                routeLine: null,
                storeLat: 7.2906,
                storeLng: 80.6337,
                orderType: @entangle('order_type').live,

                init() {
                    this.$watch('orderType', value => {
                        this.updateMapMode();
                    });
                    
                    // Delay slightly to ensure DOM is ready
                    setTimeout(() => {
                        this.initMap();
                    }, 100);
                },

                initMap() {
                    if (!document.getElementById('checkout-map')) return;

                    this.map = L.map('checkout-map').setView([this.storeLat, this.storeLng], 14);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(this.map);

                    // Store Marker
                    const storeIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: '<div style="background-color:var(--td-primary);color:white;width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 6px rgba(0,0,0,0.3);"><i class="fa-solid fa-store"></i></div>',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    });

                    this.storeMarker = L.marker([this.storeLat, this.storeLng], {
                        icon: storeIcon,
                        title: 'TastyDelight Kandy'
                    }).addTo(this.map);

                    this.storeMarker.bindPopup(`
                        <div class="text-center font-bold text-gray-800">TastyDelight Store</div>
                        <a href="https://www.google.com/maps/search/?api=1&query=${this.storeLat},${this.storeLng}" target="_blank" class="text-blue-500 text-xs underline mt-1 block">Open in Google Maps</a>
                    `);

                    // Delivery Marker
                    this.deliveryMarker = L.marker([this.storeLat + 0.01, this.storeLng + 0.01], {
                        draggable: true
                    });

                    this.deliveryMarker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.updateRoute(pos.lat, pos.lng);
                        this.reverseGeocode(pos.lat, pos.lng);
                    });

                    this.map.on('click', (e) => {
                        if (this.orderType === 'delivery') {
                            this.deliveryMarker.setLatLng(e.latlng);
                            this.updateRoute(e.latlng.lat, e.latlng.lng);
                            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
                        }
                    });

                    this.updateMapMode();
                },

                updateMapMode() {
                    if (!this.map) return;
                    
                    if (this.orderType === 'pickup') {
                        this.map.setView([this.storeLat, this.storeLng], 15);
                        this.map.dragging.disable();
                        this.map.touchZoom.disable();
                        this.map.doubleClickZoom.disable();
                        this.map.scrollWheelZoom.disable();
                        
                        if (this.map.hasLayer(this.deliveryMarker)) {
                            this.map.removeLayer(this.deliveryMarker);
                        }
                        if (this.routeLine && this.map.hasLayer(this.routeLine)) {
                            this.map.removeLayer(this.routeLine);
                        }
                        this.storeMarker.openPopup();
                    } else {
                        this.map.dragging.enable();
                        this.map.touchZoom.enable();
                        this.map.doubleClickZoom.enable();
                        this.map.scrollWheelZoom.enable();
                        this.storeMarker.closePopup();

                        if (!this.map.hasLayer(this.deliveryMarker)) {
                            this.deliveryMarker.addTo(this.map);
                            const currentPos = this.deliveryMarker.getLatLng();
                            this.updateRoute(currentPos.lat, currentPos.lng);
                            this.map.fitBounds([
                                [this.storeLat, this.storeLng],
                                [currentPos.lat, currentPos.lng]
                            ], { padding: [50, 50] });
                        }
                    }
                },

                updateRoute(lat, lng) {
                    if (this.routeLine) {
                        this.map.removeLayer(this.routeLine);
                    }
                    this.routeLine = L.polyline([
                        [this.storeLat, this.storeLng],
                        [lat, lng]
                    ], { color: '#DD6625', weight: 4, opacity: 0.7, dashArray: '10, 10' }).addTo(this.map);
                },

                async reverseGeocode(lat, lon) {
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&countrycodes=lk`);
                        const data = await res.json();
                        if(data && data.display_name) {
                            @this.set('delivery_address', data.display_name);
                        }
                    } catch(e) { console.error(e); }
                },

                locateMe() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Check if in Sri Lanka (approximate bounding box)
                            if (lat >= 5.9 && lat <= 9.9 && lng >= 79.5 && lng <= 81.9) {
                                this.map.setView([lat, lng], 15);
                                this.deliveryMarker.setLatLng([lat, lng]);
                                this.updateRoute(lat, lng);
                                this.reverseGeocode(lat, lng);
                            } else {
                                alert("Sorry, we only deliver within Sri Lanka.");
                            }
                        }, () => {
                            alert("Unable to retrieve your location.");
                        });
                    }
                }
            }
        }

        // Stripe Logic when Step 3 is activated
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ component, el }) => {
                const step = @this.get('step');
                const orderType = @this.get('order_type');
                const paymentMethod = @this.get('payment_method');
                const clientSecret = '{{ $clientSecret }}';
                
                if (step === 3 && orderType === 'delivery' && paymentMethod === 'card') {
                    const formButton = document.getElementById('submit');
                    if(!formButton || formButton.dataset.initialized) return;
                    formButton.dataset.initialized = true;

                    if (clientSecret === 'simulated_test_secret') {
                        formButton.addEventListener('click', (e) => {
                            e.preventDefault();
                            document.querySelector('#button-text').classList.add('hidden');
                            document.querySelector('#spinner').classList.remove('hidden');
                            formButton.disabled = true;
                            setTimeout(() => { @this.processOrder(); }, 1500);
                        });
                    } else {
                        if (typeof Stripe === 'undefined') {
                            const stripeScript = document.createElement('script');
                            stripeScript.src = "https://js.stripe.com/v3/";
                            document.head.appendChild(stripeScript);
                            stripeScript.onload = () => initStripe(clientSecret, formButton);
                        } else {
                            initStripe(clientSecret, formButton);
                        }
                    }
                }
            });
            
            function initStripe(clientSecret, formButton) {
                const stripe = Stripe('{{ env('STRIPE_KEY') }}');
                const elements = stripe.elements({ clientSecret });
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
            }
        });
    </script>
</div>
