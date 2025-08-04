<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index()
    {
        if (Auth::check()) {
            // Get cart items from database for logged in user
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();
        } else {
            // Get cart items from session for guest
            $cartItems = collect(Session::get('cart', []));
            
            if ($cartItems->isNotEmpty()) {
                $productIds = $cartItems->pluck('product_id')->toArray();
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
                
                $cartItems = $cartItems->map(function ($item) use ($products) {
                    // $item di sini array, jadi akses pakai ['key']
                    $item['product'] = $products[$item['product_id']] ?? null;
                    // Setelah semua properti di-set, baru cast ke object
                    return (object) $item;
                })->filter(function ($item) {
                    // $item sudah object di sini
                    return $item->product !== null;
                });
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
        
        return view('customer.cart', compact('cartItems', 'subtotal', 'discount', 'total', 'coupon'));
    }
    
    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Check if product is active and in stock
        if (!$product->is_active || $product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available or insufficient stock',
            ]);
        }
        
        if (Auth::check()) {
            // Add to database cart for logged in user
            $cartItem = Cart::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => $request->quantity,
                ]
            );
            
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            // Add to session cart for guest
            $cart = Session::get('cart', []);
            
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_id'] == $product->id) {
                    $item['quantity'] = $request->quantity;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $cart[] = [
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                ];
            }
            
            Session::put('cart', $cart);
            
            $cartCount = collect($cart)->sum('quantity');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => $cartCount,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }
    
    /**
     * Update cart item quantity.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Check if product is active and in stock
        if (!$product->is_active || $product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available or insufficient stock',
            ]);
        }
        
        if (Auth::check()) {
            // Update database cart for logged in user
            Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->update(['quantity' => $request->quantity]);
                
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->with('product')
                ->first();
                
            $subtotal = $cartItem->subtotal;
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            // Update session cart for guest
            $cart = Session::get('cart', []);
            
            foreach ($cart as &$item) {
                if ($item['product_id'] == $product->id) {
                    $item['quantity'] = $request->quantity;
                    break;
                }
            }
            
            Session::put('cart', $cart);
            
            $price = $product->discount_price ?? $product->price;
            $subtotal = $price * $request->quantity;
            $cartCount = collect($cart)->sum('quantity');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'cart_count' => $cartCount,
                'formatted_subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }
    
    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        if (Auth::check()) {
            // Remove from database cart for logged in user
            Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->delete();
                
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            // Remove from session cart for guest
            $cart = Session::get('cart', []);
            
            foreach ($cart as $key => $item) {
                if ($item['product_id'] == $request->product_id) {
                    unset($cart[$key]);
                    break;
                }
            }
            
            Session::put('cart', array_values($cart));
            
            $cartCount = collect($cart)->sum('quantity');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cartCount,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
    
    /**
     * Clear the cart.
     */
    public function clear()
    {
        if (Auth::check()) {
            // Clear database cart for logged in user
            Cart::where('user_id', Auth::id())->delete();
        } else {
            // Clear session cart for guest
            Session::forget('cart');
        }
        
        // Also clear any applied coupon
        Session::forget('coupon_code');
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared');
    }
    
    /**
     * Apply a coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);
        
        $coupon = Coupon::where('code', $request->coupon_code)
            ->active()
            ->validByDate()
            ->hasAvailableUsage()
            ->first();
        
        if (!$coupon) {
            return back()->with('error', 'Invalid or expired coupon code');
        }
        
        // Calculate cart subtotal to check minimum amount
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        } else {
            $cartItems = collect(Session::get('cart', []));
            
            if ($cartItems->isNotEmpty()) {
                $productIds = $cartItems->pluck('product_id')->toArray();
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
                
                $cartItems = $cartItems->map(function ($item) use ($products) {
                    $item['product'] = $products[$item['product_id']] ?? null;
                    return $item;
                })->filter(function ($item) {
                    return $item['product'] !== null;
                });
            }
        }
        
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        if ($subtotal < $coupon->min_amount) {
            return back()->with('error', "Minimum order amount for this coupon is Rp " . number_format($coupon->min_amount, 0, ',', '.'));
        }
        
        // Store coupon code in session
        Session::put('coupon_code', $coupon->code);
        
        return back()->with('success', 'Coupon applied successfully');
    }
    
    /**
     * Remove the applied coupon.
     */
    public function removeCoupon()
    {
        Session::forget('coupon_code');
        return back()->with('success', 'Coupon removed');
    }
}
