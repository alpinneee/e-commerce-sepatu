<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        // Check if cart is empty
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        } else {
            $sessionCart = Session::get('cart', []);
            
            if (empty($sessionCart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
            
            $productIds = array_column($sessionCart, 'product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            $cartItems = collect();
            foreach ($sessionCart as $item) {
                if (isset($products[$item['product_id']])) {
                    $cartItem = (object) [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'product' => $products[$item['product_id']],
                    ];
                    $cartItems->push($cartItem);
                }
            }
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        $discount = 0;
        
        foreach ($cartItems as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        // Get applied coupon
        $coupon = null;
        $couponCode = Session::get('coupon_code');
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_amount) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                // Remove invalid coupon
                Session::forget('coupon_code');
            }
        }
        
        $total = $subtotal - $discount;
        
        // Get shipping addresses for logged in user
        $shippingAddresses = [];
        if (Auth::check()) {
            $shippingAddresses = ShippingAddress::where('user_id', Auth::id())->get();
        }
        
        return view('customer.checkout', compact(
            'cartItems',
            'subtotal',
            'discount',
            'total',
            'coupon',
            'shippingAddresses'
        ));
    }
    
    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:bank_transfer,cod',
            'save_address' => 'nullable|boolean',
        ]);
        
        // Get cart items
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        } else {
            $sessionCart = Session::get('cart', []);
            
            if (empty($sessionCart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
            
            $productIds = array_column($sessionCart, 'product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            $cartItems = collect();
            foreach ($sessionCart as $item) {
                if (isset($products[$item['product_id']])) {
                    $cartItem = (object) [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'product' => $products[$item['product_id']],
                    ];
                    $cartItems->push($cartItem);
                }
            }
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        $discount = 0;
        $shippingCost = 0; // You can implement shipping cost calculation here
        
        foreach ($cartItems as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        // Apply coupon if available
        $coupon = null;
        $couponCode = Session::get('coupon_code');
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_amount) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }
        
        $total = $subtotal - $discount + $shippingCost;
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Create shipping address if requested
            if (Auth::check() && $request->has('save_address') && $request->save_address) {
                ShippingAddress::create([
                    'user_id' => Auth::id(),
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'province' => $request->province,
                    'postal_code' => $request->postal_code,
                    'is_default' => !ShippingAddress::where('user_id', Auth::id())->exists(),
                ]);
            }
            
            // Format shipping address for order
            $shippingAddress = json_encode([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
            ]);
            
            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(Str::random(10));
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $total,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $shippingAddress,
                'notes' => $request->notes,
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                $price = $item->product->discount_price ?? $item->product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total' => $price * $item->quantity,
                ]);
                
                // Update product stock
                $product = Product::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();
            }
            
            // Update coupon usage if applied
            if ($coupon) {
                $coupon->incrementUsage();
            }
            
            // Clear cart
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                Session::forget('cart');
            }
            
            // Clear coupon
            Session::forget('coupon_code');
            
            DB::commit();
            
            // Store order in session for thank you page
            Session::put('completed_order', $order->id);
            
            return redirect()->route('checkout.success');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the checkout success page.
     */
    public function success()
    {
        $orderId = Session::get('completed_order');
        
        if (!$orderId) {
            return redirect()->route('home');
        }
        
        $order = Order::with(['items.product'])->findOrFail($orderId);
        
        // Clear the session data
        Session::forget('completed_order');
        
        return view('customer.checkout-success', compact('order'));
    }
}
