<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Get all orders for authenticated user
    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($orders);
    }
    public function adminIndex()
{
    $orders = Order::with(['user', 'orderItems.product'])->get(); // include user and products

    // Map to include item count and total price
    $ordersData = $orders->map(function ($order) {
        return [
            'id' => $order->id,
            'user' => $order->user,
            'status' => $order->status,
            'created_at' => $order->created_at->format('Y-m-d H:i'),
            'total' => $order->orderItems->sum(fn($item) => $item->quantity * $item->price),
            'items_count' => $order->orderItems->sum('quantity'),
        ];
    });

    return response()->json(['orders' => $ordersData]);
}

public function adminShow($id)
{
    $order = Order::with(['user', 'orderItems.product'])->find($id);

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Map order items
    $orderData = [
        'id' => $order->id,
        'user' => $order->user,
        'status' => $order->status,
        'total' => $order->orderItems->sum(fn($item) => $item->quantity * $item->price),
        'items' => $order->orderItems->map(function($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'product' => $item->product,
            ];
        }),
        'created_at' => $order->created_at,
    ];

    return response()->json(['order' => $orderData]);
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,paid,shipped,delivered,cancelled'
    ]);

    $order = Order::find($id);
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    $order->status = $request->status;
    $order->save();

    return response()->json(['message' => 'Order status updated', 'order' => $order]);
}



    // Get single order
    public function show($id)
    {
        $order = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($order);
    }

    // Create order from cart
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|in:COD,Credit Card',
        ]);

        $userId = Auth::id();
        $cartItems = CartItem::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Calculate total
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Create order with transaction
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $userId,
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            // Clear cart
            CartItem::where('user_id', $userId)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('orderItems.product')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order failed: ' . $e->getMessage()], 500);
        }
    }

}