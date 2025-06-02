namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show($userId)
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        return new CartResource($cart->load('items.product'));
    }

    public function addItem(Request $request, $userId)
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->items()->updateOrCreate(
            ['product_id' => $validated['product_id']],
            ['quantity' => $validated['quantity']]
        );

        return new CartResource($cart->fresh()->load('items.product'));
    }

    public function removeItem($userId, $itemId)
    {
        CartItem::where('cart_id', $userId)
               ->where('id', $itemId)
               ->delete();

        return $this->show($userId);
    }
}