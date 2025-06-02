namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(
            auth()->user()->orders()->with('products')->paginate(5)
        );
    }

    public function store(Request $request)
    {
        $cart = auth()->user()->cart;

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $cart->total,
            'status' => 'pending'
        ]);

        foreach ($cart->items as $item) {
            $order->products()->attach($item->product_id, [
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        $cart->items()->delete();

        return new OrderResource($order->load('products'));
    }
}