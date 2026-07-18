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
    public $delivery_date = '';
    public $delivery_time = 'asap';
    public $pickup_date = '';
    public $pickup_time = 'asap';
    
    // Payment Method (for delivery only, pickup is pay at counter)
    public string $payment_method = 'cash'; 
    
    // Notes
    public string $preparation_note = '';
    public string $delivery_note = '';

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
        
        if ($propertyName === 'order_type') {
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        if ($this->cart && $this->cart->items->isNotEmpty()) {
            $this->subtotal = $this->cart->items->sum(fn ($item) => $item->quantity * $item->product->price);
            $this->tax_amount = $this->subtotal * 0.05; // 5% tax
            $this->delivery_fee = $this->order_type === 'delivery' ? 5.00 : 0.00; // $5 flat fee for delivery
            $this->total = $this->subtotal + $this->tax_amount + $this->delivery_fee;
        }
    }

    public function mount()
    {
        $this->cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        if (!$this->cart || $this->cart->items->isEmpty()) {
            return redirect()->route('user.cart');
        }

        if (!$this->validateStock()) {
            return redirect()->route('user.cart');
        }

        $this->calculateTotals();
        
        $this->delivery_date = date('Y-m-d');
        $this->pickup_date = date('Y-m-d');

        $stripeSecret = env('STRIPE_SECRET');
        if ($stripeSecret) {
            Stripe::setApiKey($stripeSecret);
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => max((int)($this->total * 100), 50),
                    'currency' => 'usd',
                    'metadata' => ['user_id' => Auth::id()],
                ]);
                $this->clientSecret = $paymentIntent->client_secret;
            } catch (\Stripe\Exception\ApiErrorException $e) {
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

    public function goToReview()
    {
        if (!$this->validateStock()) {
            return redirect()->route('user.cart');
        }

        // Validation could be added here
        if ($this->order_type === 'delivery' && empty($this->delivery_address)) {
            $this->addError('delivery_address', 'Delivery address is required.');
            return;
        }
        $this->step = 2;
    }

    public function goBack()
    {
        $this->step = 1;
    }

    public function confirmOrder()
    {
        if (!$this->validateStock()) {
            return redirect()->route('user.cart');
        }

        if ($this->order_type === 'pickup' || ($this->order_type === 'delivery' && $this->payment_method === 'cash')) {
            $this->processOrder();
        } else {
            // Delivery + Card
            $this->step = 3;
        }
    }

    public function processOrder()
    {
        if (!$this->validateStock()) {
            return redirect()->route('user.cart');
        }

        $order = null;
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
                'delivery_date' => $this->order_type === 'delivery' ? $this->delivery_date : null,
                'delivery_time' => $this->order_type === 'delivery' ? $this->delivery_time : null,
                'pickup_date' => $this->order_type === 'pickup' ? $this->pickup_date : null,
                'pickup_time' => $this->order_type === 'pickup' ? $this->pickup_time : null,
                'preparation_note' => $this->preparation_note,
                'delivery_note' => $this->order_type === 'delivery' ? $this->delivery_note : null,
            ]);

            foreach ($this->cart->items as $item) {
                $order->products()->attach($item->product_id, [
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                $product = Product::find($item->product_id);
                if ($product && $product->stock >= $item->quantity) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            $this->cart->items()->delete();
            $this->cart->delete();
        });

        try {
            \Illuminate\Support\Facades\Mail::to(Auth::user()->email)->send(new \App\Mail\OrderReceiptMailable($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
        }

        session()->flash('success', 'Order placed successfully! Check your email for the receipt.');
        return redirect()->route('user.orders');
    }

    public function render()
    {
        return view('livewire.user.user-checkout')->layout('layouts.user');
    }
}
