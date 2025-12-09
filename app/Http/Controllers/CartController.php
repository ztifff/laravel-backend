<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Get all cart items for the logged-in user
    public function index()
    {
        $userId = Auth::id();
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();

        $cart = $cartItems->map(function($item) {
            return [
                'id' => $item->id,          // cart item ID
                'quantity' => $item->quantity,
                'product' => $item->product
            ];
        });

        return response()->json(['cartItems' => $cart]);
    }

    // Add item to cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $quantity = $request->quantity ?? 1;

        $cartItem = CartItem::where('user_id', $userId)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $quantity
            ]);
        }

        return $this->index(); // return updated cart
    }

    // Update quantity of a cart item
    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('id', $cartItemId) // use cart item ID now
                            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return $this->index(); // return updated cart
    }

    // Remove item from cart
    public function destroy($cartItemId)
    {
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('id', $cartItemId) // use cart item ID
                            ->firstOrFail();

        $cartItem->delete();

        return $this->index(); // return updated cart
    }
}
