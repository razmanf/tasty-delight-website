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

class UserCheckout extends Component
{
    public $cart;
    public $total = 0;
    public $clientSecret;

    public $address = '';
    public $phone = '';
    public $notes = '';

    public function mount()
    {
        $this->cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        if (!$this->cart || $this->cart->items->isEmpty()) {
            return redirect()->route('user.cart');
        }

        $this->total = $this->cart->items->sum(fn ($item) => $item->quantity * $item->product->price);

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
                // Log error and fallback to simulated test mode
                \Illuminate\Support\Facades\Log::error('Stripe API Error: ' . $e->getMessage());
                $this->clientSecret = 'simulated_test_secret';
            }
        } else {
            // Simulated Test Mode without keys
            $this->clientSecret = 'simulated_test_secret';
        }
    }

    public function processOrder()
    {
        $order = null;
        DB::transaction(function () use (&$order) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $this->total,
                'status' => 'pending', // Requires admin manual approval
                'payment_method' => 'stripe'
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
            // Log error if mail fails but don't fail the order
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
