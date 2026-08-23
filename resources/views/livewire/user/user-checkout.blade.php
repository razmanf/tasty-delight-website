<div class="max-w-4xl mx-auto pb-10" x-data="checkoutState()">

    <h1 class="text-2xl font-bold mb-6" style="color: var(--td-text);">
        <i class="fa-solid fa-credit-card mr-2" style="color: var(--td-primary);"></i> Checkout
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- STEP 1 & 2 CONTAINER -->
        <div class="space-y-6">
            
            <!-- Fulfillment Details -->
            <div>
                <div class="td-card">
                    <h2 class="font-bold text-lg mb-4" style="color: var(--td-text);">{{ config('labels.order_details', 'Fulfillment Method') }}</h2>
                    
                    <!-- Toggle Delivery/Pickup (Pill Selector) -->
                    <div class="relative flex items-center p-1 bg-gray-100 dark:bg-gray-800/50 rounded-full mb-6 w-full max-w-sm border shadow-inner" style="border-color: var(--td-border);">
                        <!-- Sliding Background Pill -->
                        <div class="absolute top-1 bottom-1 w-[calc(50%-4px)] bg-white dark:bg-gray-700 shadow-md rounded-full transition-transform duration-300 ease-out z-0"
                             :class="orderType === 'pickup' ? 'translate-x-[calc(100%+4px)]' : 'translate-x-0'"></div>
                        
                        <button @click="orderType = 'delivery'" class="relative flex-1 py-2 text-sm font-bold transition-colors z-10"
                                :class="orderType === 'delivery' ? 'text-[var(--td-primary)]' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                            <i class="fa-solid fa-person-biking mr-1.5"></i> Delivery
                        </button>
                        <button @click="orderType = 'pickup'" class="relative flex-1 py-2 text-sm font-bold transition-colors z-10"
                                :class="orderType === 'pickup' ? 'text-[var(--td-primary)]' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'">
                            <i class="fa-solid fa-store mr-1.5"></i> Pickup
                        </button>
                    </div>

                    <!-- Map Container -->
                    <div class="rounded-xl overflow-hidden border relative z-0 mb-4" style="border-color: var(--td-border); height: 250px;" wire:ignore>
                        <div id="checkout-map" class="w-full h-full z-10"></div>
                        <button x-show="orderType === 'delivery'" @click="locateMe()" type="button" class="absolute bottom-4 right-4 bg-white text-black w-10 h-10 flex items-center justify-center rounded-full shadow-lg z-[400] hover:bg-gray-100" title="Find My Location" style="display: none;">
                            <i class="fa-solid fa-location-crosshairs text-lg"></i>
                        </button>
                    </div>

                    <!-- Delivery Address Input (If Delivery) -->
                    <div x-show="orderType === 'delivery'" class="mb-4" @click.away="showSuggestions = false" style="display: none;">
                        <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Delivery Address</label>
                        <div class="relative">
                            <input type="text" 
                                   x-model="addressQuery"
                                   @input.debounce.300ms="fetchSuggestions()"
                                   @focus="if(addressQuery && addressQuery.length >= 3) fetchSuggestions()"
                                   class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700" 
                                   placeholder="Enter delivery address (Sri Lanka)">
                            
                            <!-- Dropdown -->
                            <div x-show="showSuggestions && suggestions.length > 0" 
                                 x-transition
                                 class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg max-h-60 overflow-y-auto" 
                                 style="display: none;">
                                <template x-for="item in suggestions" :key="item.place_id">
                                    <div @click="selectSuggestion(item)" class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b last:border-b-0 border-gray-100 dark:border-gray-700 transition-colors">
                                        <div class="font-bold text-sm" style="color: var(--td-text);" x-text="item.display_name.split(',')[0]"></div>
                                        <div class="text-xs mt-0.5 line-clamp-1" style="color: var(--td-muted);" x-text="item.display_name"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('delivery_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date and Time -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Date</label>
                            <!-- Delivery Date -->
                            <div x-show="orderType === 'delivery'" class="relative" wire:ignore>
                                <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d', minDate: 'today', disableMobile: true, onChange: (dates, dateStr) => { @this.set('delivery_date', dateStr); } })" class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 cursor-pointer bg-white" placeholder="Select Date">
                                <i class="fa-regular fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                            <!-- Pickup Date -->
                            <div x-show="orderType === 'pickup'" class="relative" style="display: none;" wire:ignore>
                                <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d', minDate: 'today', disableMobile: true, onChange: (dates, dateStr) => { @this.set('pickup_date', dateStr); } })" class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 cursor-pointer bg-white" placeholder="Select Date">
                                <i class="fa-regular fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Time</label>
                            <!-- Delivery Time Custom Dropdown -->
                            <div x-show="orderType === 'delivery'" x-data="{ open: false, selected: @entangle('delivery_time').live }" class="relative">
                                <button @click="open = !open" @click.away="open = false" type="button" class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 flex justify-between items-center text-base">
                                    <span x-text="selected === 'asap' ? 'ASAP (30-45 min)' : (selected === '12pm' ? '12:00 PM' : (selected === '1pm' ? '1:00 PM' : (selected === '6pm' ? '6:00 PM' : (selected === '7pm' ? '7:00 PM' : selected))))"></span>
                                    <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg py-1">
                                    <template x-for="option in [{val:'asap', label:'ASAP (30-45 min)'}, {val:'12pm', label:'12:00 PM'}, {val:'1pm', label:'1:00 PM'}, {val:'6pm', label:'6:00 PM'}, {val:'7pm', label:'7:00 PM'}]">
                                        <button @click="selected = option.val; open = false" type="button" class="w-full text-left px-4 py-2.5 text-base flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="selected === option.val ? 'text-gray-900 dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-300 font-normal'">
                                            <span x-text="option.label"></span>
                                            <i class="fa-solid fa-check text-[#DD6625]" x-show="selected === option.val"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Pickup Time Custom Dropdown -->
                            <div x-show="orderType === 'pickup'" x-data="{ open: false, selected: @entangle('pickup_time').live }" class="relative" style="display: none;">
                                <button @click="open = !open" @click.away="open = false" type="button" class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 flex justify-between items-center text-base">
                                    <span x-text="selected === 'asap' ? 'ASAP (15-20 min)' : (selected === '12pm' ? '12:00 PM' : (selected === '1pm' ? '1:00 PM' : (selected === '6pm' ? '6:00 PM' : (selected === '7pm' ? '7:00 PM' : selected))))"></span>
                                    <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg py-1">
                                    <template x-for="option in [{val:'asap', label:'ASAP (15-20 min)'}, {val:'12pm', label:'12:00 PM'}, {val:'1pm', label:'1:00 PM'}, {val:'6pm', label:'6:00 PM'}, {val:'7pm', label:'7:00 PM'}]">
                                        <button @click="selected = option.val; open = false" type="button" class="w-full text-left px-4 py-2.5 text-base flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="selected === option.val ? 'text-gray-900 dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-300 font-normal'">
                                            <span x-text="option.label"></span>
                                            <i class="fa-solid fa-check text-[#DD6625]" x-show="selected === option.val"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
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
                    <div x-show="orderType === 'delivery'" class="mt-4" style="display: none;">
                        <label class="block text-sm font-medium mb-2" style="color: var(--td-text);">Payment Method</label>
                        <div x-data="{ open: false, selected: @entangle('payment_method').live }" class="relative">
                            <button @click="open = !open" @click.away="open = false" type="button" class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 flex justify-between items-center text-base">
                                <span>
                                    <i class="fa-solid mr-2" :class="selected === 'cash' ? 'fa-money-bill text-green-500' : 'fa-credit-card text-blue-500'"></i> 
                                    <span x-text="selected === 'cash' ? 'Cash on Delivery' : 'Pay by Card'"></span>
                                </span>
                                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg py-1">
                                <template x-for="option in [{val:'cash', label:'Cash on Delivery', icon:'fa-money-bill text-green-500'}, {val:'card', label:'Pay by Card', icon:'fa-credit-card text-blue-500'}]">
                                    <button @click="selected = option.val; open = false" type="button" class="w-full text-left px-4 py-2.5 text-base flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="selected === option.val ? 'text-gray-900 dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-300 font-normal'">
                                        <span>
                                            <i class="fa-solid mr-2" :class="option.icon"></i>
                                            <span x-text="option.label"></span>
                                        </span>
                                        <i class="fa-solid fa-check text-[#DD6625]" x-show="selected === option.val"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6 border-t pt-4" style="border-color: var(--td-border);">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Preparation Note (Optional)</label>
                                <textarea wire:model.blur="preparation_note" class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 h-20" placeholder="E.g., Extra spicy, no onions..."></textarea>
                            </div>
                            <div x-show="orderType === 'delivery'" style="display: none;">
                                <label class="block text-sm font-medium mb-1" style="color: var(--td-text);">Delivery Note (Optional)</label>
                                <textarea wire:model.blur="delivery_note" class="w-full rounded-xl border bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 h-20" placeholder="E.g., Leave at the door..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: ORDER SUMMARY & STRIPE -->
        <div class="space-y-6">
            <div class="td-card h-fit">
                <h2 class="font-bold text-lg mb-4" style="color: var(--td-text);">Order Summary</h2>
                <div class="space-y-3 mb-4">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <span style="color: var(--td-text);">{{ $item->quantity }}x {{ $item->product->name }} <span class="text-xs" style="color: var(--td-muted); margin-left: 0.25rem;">(${{ number_format($item->product->price, 2) }} each)</span></span>
                            <span class="font-medium" style="color: var(--td-text);">$ {{ number_format($item->quantity * $item->product->price, 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t pt-3 mt-3 flex justify-between items-center text-sm" style="border-color: var(--td-border); color: var(--td-text);">
                        <span>Subtotal</span>
                        <span>$ {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm mt-2" style="color: var(--td-text);">
                        <span>Tax (8%)</span>
                        <span>$ {{ number_format($tax_amount, 2) }}</span>
                    </div>
                    <div x-show="orderType === 'delivery'" class="flex justify-between items-center text-sm mt-2" style="color: var(--td-text); display: none;">
                        <span>Delivery Fee</span>
                        <span>$ {{ number_format($delivery_fee, 2) }}</span>
                    </div>
                    <div class="border-t pt-3 mt-3 flex justify-between items-center font-bold text-lg" style="border-color: var(--td-border);">
                        <span style="color: var(--td-text);">Total</span>
                        <span style="color: var(--td-primary);">$ {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @if($step === 1)
                    <button wire:click="confirmOrder" wire:loading.attr="disabled" class="td-btn-primary w-full py-3 justify-center">
                        @if($order_type === 'pickup' || ($order_type === 'delivery' && $payment_method === 'cash'))
                            <i class="fa-solid fa-check mr-2"></i> Place Order Now
                        @else
                            <i class="fa-solid fa-lock mr-2"></i> Proceed to Payment
                        @endif
                    </button>
                @endif
                
                @if($step === 3 && $order_type === 'delivery' && $payment_method === 'card')
                    <div class="mt-6 border-t pt-6" style="border-color: var(--td-border);" wire:ignore>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold" style="color: var(--td-text);">Card Details</h3>
                        </div>
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
                paymentMethod: @entangle('payment_method').live,
                step: @entangle('step'),
                addressQuery: @entangle('delivery_address'),
                deliveryLat: @entangle('delivery_lat'),
                deliveryLng: @entangle('delivery_lng'),
                clientSecret: @entangle('clientSecret'),
                showSuggestions: false,
                suggestions: [],

                async fetchSuggestions() {
                    if (!this.addressQuery || this.addressQuery.length < 3) {
                        this.suggestions = [];
                        this.showSuggestions = false;
                        return;
                    }
                    try {
                        const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(this.addressQuery)}&lat=${this.storeLat}&lon=${this.storeLng}&limit=10`);
                        const data = await res.json();
                        
                        this.suggestions = data.features
                            .filter(f => f.properties.countrycode === 'LK' || f.properties.country === 'Sri Lanka')
                            .slice(0, 5)
                            .map(f => {
                                let displayName = f.properties.name || '';
                                const parts = [];
                                if (f.properties.street) parts.push(f.properties.street);
                                if (f.properties.city) parts.push(f.properties.city);
                                if (f.properties.state) parts.push(f.properties.state);
                                
                                if (displayName && parts.length > 0) {
                                    displayName += ', ' + parts.join(', ');
                                } else if (!displayName && parts.length > 0) {
                                    displayName = parts.join(', ');
                                } else if (!displayName) {
                                    displayName = 'Unknown location';
                                }

                                return {
                                    place_id: f.properties.osm_id || Math.random(),
                                    lat: f.geometry.coordinates[1],
                                    lon: f.geometry.coordinates[0],
                                    display_name: displayName
                                };
                            })
                            .filter(item => item.display_name !== 'Unknown location');
                            
                        this.showSuggestions = this.suggestions.length > 0;
                    } catch (e) {
                        console.error(e);
                    }
                },
                
                selectSuggestion(item) {
                    this.addressQuery = item.display_name;
                    this.showSuggestions = false;
                    
                    const lat = parseFloat(item.lat);
                    const lng = parseFloat(item.lon);
                    
                    if (this.orderType === 'delivery') {
                        if (lat >= 5.9 && lat <= 9.9 && lng >= 79.5 && lng <= 81.9) {
                            this.map.setView([lat, lng], 15);
                            this.deliveryMarker.setLatLng([lat, lng]);
                            this.updateRoute(lat, lng);
                            this.deliveryLat = lat;
                            this.deliveryLng = lng;
                        } else {
                            alert("Sorry, we only deliver within Sri Lanka.");
                        }
                    }
                },

                init() {
                    this.$nextTick(() => this.boot());
                    return () => this.teardown();
                },

                boot() {
                    this.teardown();

                    this.unwatchOrderType = this.$watch('orderType', value => {
                        this.updateMapMode();
                    });

                    this.unwatchStep = this.$watch('step', value => {
                        setTimeout(() => {
                            if (this.map) this.map.invalidateSize();
                        }, 100);
                    });

                    this.mapInitTimeout = setTimeout(() => {
                        this.initMap();
                    }, 100);
                },

                teardown() {
                    clearTimeout(this.mapInitTimeout);
                    if (typeof this.unwatchOrderType === 'function') this.unwatchOrderType();
                    if (typeof this.unwatchStep === 'function') this.unwatchStep();
                    if (typeof this.unwatchStepForPayment === 'function') this.unwatchStepForPayment();
                    if (this.map) {
                        this.map.remove();
                        this.map = null;
                    }
                },

                initMap() {
                    this.map = L.map('checkout-map').setView([this.storeLat, this.storeLng], 15);
                    
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(this.map);

                    this.storeMarker = L.marker([this.storeLat, this.storeLng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(this.map);

                    this.storeMarker.bindPopup(
                        '<' + 'div class="text-center font-bold text-gray-800">TastyDelight Store<' + '/div>' +
                        '<' + 'a href="https://www.google.com/maps/search/?api=1&query=' + this.storeLat + ',' + this.storeLng + '" target="_blank" class="text-blue-500 text-xs underline mt-1 block">Open in Google Maps<' + '/a>'
                    );

                    this.deliveryMarker = L.marker([this.storeLat + 0.01, this.storeLng + 0.01], {
                        draggable: true
                    });

                    this.deliveryMarker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.updateRoute(pos.lat, pos.lng);
                        this.reverseGeocode(pos.lat, pos.lng);
                        this.deliveryLat = pos.lat;
                        this.deliveryLng = pos.lng;
                    });

                    this.map.on('click', (e) => {
                        if (this.orderType === 'delivery') {
                            this.deliveryMarker.setLatLng(e.latlng);
                            this.updateRoute(e.latlng.lat, e.latlng.lng);
                            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
                            this.deliveryLat = e.latlng.lat;
                            this.deliveryLng = e.latlng.lng;
                        }
                    });

                    this.updateMapMode();

                    this.unwatchStepForPayment = this.$watch('step', (val) => {
                        if (val === 3 && this.orderType === 'delivery' && this.paymentMethod === 'card') {
                            this.initPayment();
                        }
                    });

                    if (this.step === 3 && this.orderType === 'delivery' && this.paymentMethod === 'card') {
                        this.initPayment();
                    }
                },

                updateMapMode() {
                    if (!this.map) return;
                    
                    if (this.orderType === 'pickup') {
                        this.map.setView([this.storeLat, this.storeLng], 15);
                        this.map.dragging.disable();
                        this.map.touchZoom.disable();
                        this.map.doubleClickZoom.disable();
                        this.map.scrollWheelZoom.disable();
                        this.storeMarker.openPopup();
                        
                        if (this.map.hasLayer(this.deliveryMarker)) {
                            this.map.removeLayer(this.deliveryMarker);
                        }
                        if (this.routeLine && this.map.hasLayer(this.routeLine)) {
                            this.map.removeLayer(this.routeLine);
                        }
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
                            this.deliveryLat = currentPos.lat;
                            this.deliveryLng = currentPos.lng;
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
                            this.addressQuery = data.display_name;
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
                },

                initPayment() {
                    this.$nextTick(() => {
                        const formButton = document.getElementById('submit');
                        if(!formButton || formButton.dataset.initialized) return;
                        formButton.dataset.initialized = true;

                        if (this.clientSecret === 'simulated_test_secret') {
                            formButton.addEventListener('click', (e) => {
                                e.preventDefault();
                                document.querySelector('#button-text').classList.add('hidden');
                                document.querySelector('#spinner').classList.remove('hidden');
                                formButton.disabled = true;
                                setTimeout(() => { this.$wire.processOrder(); }, 1500);
                            });
                        } else {
                            if (typeof Stripe === 'undefined') {
                                const stripeScript = document.createElement('script');
                                stripeScript.src = "https://js.stripe.com/v3/";
                                document.head.appendChild(stripeScript);
                                stripeScript.onload = () => this.mountStripe(formButton);
                            } else {
                                this.mountStripe(formButton);
                            }
                        }
                    });
                },

                mountStripe(formButton) {
                    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
                    const elements = stripe.elements({ clientSecret: this.clientSecret });
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
                            this.$wire.processOrder();
                        }
                    });
                }
            }
        }
    </script>
</div>
