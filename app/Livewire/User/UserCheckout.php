<?php

namespace App\Livewire\User;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;

#[Title('Checkout')]
class UserCheckout extends Component
{
    public $cart;
    
    public $subtotal = 0;
    public $tax_amount = 0;
    public $delivery_fee = 0;
    public $total = 0;
    
    public $clientSecret;

    // Multi-step state
    public int $step = 1;

    // Fulfillment Details
    public string $order_type = 'delivery'; // 'delivery' or 'pickup'
    public $delivery_address = '';
    public $delivery_lat;
    public $delivery_lng;
    public $delivery_date = '';
    public $delivery_time = 'asap';
    public $pickup_date = '';
    public $pickup_time = 'asap';
    
    // Payment Method (for delivery only, pickup is pay at counter)
    public string $payment_method = 'cash'; 
    
    // Notes
    public string $preparation_note = '';
    public string $delivery_note = '';
    
    // Promo
    public string $promo_code = '';
    public string $applied_promo = '';
    public $discount_amount = 0;

    protected $rules = [
        'delivery_address' => 'required_if:order_type,delivery',
        'delivery_date' => 'required_if:order_type,delivery',
        'delivery_time' => 'required_if:order_type,delivery',
        'pickup_date' => 'required_if:order_type,pickup',
        'pickup_time' => 'required_if:order_type,pickup',
        'payment_method' => 'required_if:order_type,delivery',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        if (in_array($propertyName, ['order_type', 'payment_method'])) {
            $this->step = 1;
        }
        
        if ($propertyName === 'order_type') {
            $this->calculateTotals();
        }
    }
    
    public function applyPromoCode()
    {
        if (strtoupper($this->promo_code) === 'TASTY20') {
            $this->applied_promo = 'TASTY20';
            $this->promo_code = '';
            $this->calculateTotals();
        } else {
            session()->flash('promo_error', 'Invalid promo code');
        }
    }
    
    public function removePromoCode()
    {
        $this->applied_promo = '';
        $this->discount_amount = 0;
        $this->calculateTotals();
    }

    /**
     * Return from the card-payment step (step 3) back to the combined form (step 1).
     */
    public function goBack()
    {
        $this->step = 1;
    }

    public function calculateTotals()
    {
        if ($this->cart && $this->cart->items->isNotEmpty()) {
            $this->subtotal = $this->cart->items->sum(fn ($item) => $item->quantity * $item->product->price);
            
            if ($this->applied_promo === 'TASTY20') {
                $this->discount_amount = round($this->subtotal * 0.20, 2);
            } else {
                $this->discount_amount = 0;
            }
            
            $discounted_subtotal = max(0, $this->subtotal - $this->discount_amount);
            
            $this->tax_amount = round($discounted_subtotal * 0.05, 2); // 5% tax
            $this->delivery_fee = $this->order_type === 'delivery' ? 5.00 : 0.00; // $5 flat fee for delivery
            $this->total = round($discounted_subtotal + $this->tax_amount + $this->delivery_fee, 2);
            
            $this->updateStripePaymentIntent();
        }
    }

    public function mount()
    {
        $this->cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        if (!$this->cart || $this->cart->items->isEmpty()) {
            // Use $this->redirect() (Livewire-idiomatic) to avoid flash-then-redirect
            // in SPA-navigate contexts.
            return $this->redirect(route('user.cart'), navigate: false);
        }

        if (!$this->validateStock()) {
            return $this->redirect(route('user.cart'), navigate: false);
        }

        $this->calculateTotals();
        
        $this->delivery_date = date('Y-m-d');
        $this->pickup_date = date('Y-m-d');

        $this->updateStripePaymentIntent();
    }
    
    public function updateStripePaymentIntent()
    {
        $stripeSecret = env('STRIPE_SECRET');
        if ($stripeSecret) {
            Stripe::setApiKey($stripeSecret);
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => max((int)round($this->total * 100), 50),
                    'currency' => 'usd',
                    'metadata' => ['user_id' => Auth::id()],
                ]);
                $this->clientSecret = $paymentIntent->client_secret;
                $this->dispatch('payment-intent-updated', clientSecret: $this->clientSecret);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Stripe API Error: ' . $e->getMessage());
                $this->clientSecret = 'simulated_test_secret';
            }
        } else {
            $this->clientSecret = 'simulated_test_secret';
        }
    }

    public function validateStock(): bool
    {
        if ($this->cart) {
            foreach ($this->cart->items as $item) {
                // Refresh product to get latest stock
                $product = Product::find($item->product_id);
                if (!$product || $product->stock < $item->quantity) {
                    session()->flash('error', 'An item in your cart is no longer available in the requested quantity. Please adjust your cart.');
                    return false;
                }
            }
        }
        return true;
    }

    public function confirmOrder()
    {
        // Validate all required fulfillment fields.
        // (Previously done in the now-removed goToReview step.)
        $fieldsToValidate = $this->order_type === 'delivery'
            ? ['delivery_address', 'delivery_date', 'delivery_time', 'payment_method']
            : ['pickup_date', 'pickup_time'];

        $this->validate(collect($this->rules)->only($fieldsToValidate)->toArray());

        // Extra guard: ensure an address was actually geocoded by the map.
        if ($this->order_type === 'delivery' && empty($this->delivery_address)) {
            $this->addError('delivery_address', 'Please enter a delivery address on the map.');
            return;
        }

        if (!$this->validateStock()) {
            return $this->redirect(route('user.cart'), navigate: false);
        }

        if ($this->order_type === 'pickup' || ($this->order_type === 'delivery' && $this->payment_method === 'cash')) {
            return $this->processOrder();
        } else {
            // Delivery + Card → advance to payment step
            $this->step = 3;
        }
    }

    public function processOrder()
    {
        if (!$this->validateStock()) {
            return redirect()->route('user.cart');
        }

        $order = null;
        try {
            DB::transaction(function () use (&$order) {
                $actualPaymentMethod = $this->order_type === 'pickup' ? 'counter' : $this->payment_method;

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $this->total,
                    'tax_amount' => $this->tax_amount,
                    'delivery_fee' => $this->delivery_fee,
                    'status' => 'pending',
                    'payment_method' => $actualPaymentMethod,
                    'order_type' => $this->order_type,
                    'delivery_address' => $this->order_type === 'delivery' ? $this->delivery_address : null,
                    'delivery_lat' => $this->order_type === 'delivery' ? $this->delivery_lat : null,
                    'delivery_lng' => $this->order_type === 'delivery' ? $this->delivery_lng : null,
                    'delivery_date' => $this->order_type === 'delivery' ? $this->delivery_date : null,
                    'delivery_time' => $this->order_type === 'delivery' ? $this->delivery_time : null,
                    'pickup_date' => $this->order_type === 'pickup' ? $this->pickup_date : null,
                    'pickup_time' => $this->order_type === 'pickup' ? $this->pickup_time : null,
                    'preparation_note' => $this->preparation_note,
                    'delivery_note' => $this->order_type === 'delivery' ? $this->delivery_note : null,
                    'promo_code' => $this->applied_promo,
                    'discount_amount' => $this->discount_amount,
                ]);

                foreach ($this->cart->items as $item) {
                    $order->products()->attach($item->product_id, [
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);

                    // Pessimistic locking to prevent inventory race conditions
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if (!$product || $product->stock < $item->quantity) {
                        throw new \Exception("Sorry, '" . ($product ? $product->name : 'an item') . "' just went out of stock!");
                    }
                    $product->decrement('stock', $item->quantity);
                }

                $this->cart->items()->delete();
                $this->cart->delete();
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order placement failed due to race condition: ' . $e->getMessage());
            session()->flash('error', $e->getMessage() . ' If you paid via card, please contact support for a prompt refund.');
            return $this->redirect(route('user.cart'), navigate: false);
        }

        try {
            \Illuminate\Support\Facades\Mail::to(Auth::user()->email)->send(new \App\Mail\OrderReceiptMailable($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
        }

        session()->flash('success', 'Order placed successfully! Check your email for the receipt.');
        $this->redirectRoute('user.orders', navigate: true);
    }

    public function render()
    {
        return view('livewire.user.user-checkout')->layout('layouts.user');
    }
}
