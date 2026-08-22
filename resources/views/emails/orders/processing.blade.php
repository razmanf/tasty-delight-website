<x-mail::message>
# Great news, {{ $order->user->name ?? 'Customer' }}!

Your order **#{{ $order->id }}** is officially **Processing**. 

This means our kitchen has received your order and we are currently preparing it fresh for you.

@if($order->order_type === 'delivery')
You can expect it to arrive shortly via delivery to:
**{{ $order->delivery_address }}**
@else
It will be ready for pickup soon at our Kandy location.
@endif

Thanks for choosing Tasty Delight!

<x-mail::button :url="url('/user/orders')">
View Order Status
</x-mail::button>
</x-mail::message>
