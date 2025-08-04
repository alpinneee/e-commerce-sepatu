<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

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
        Log::info('Checkout process started');
        Log::info('Request data:', $request->all());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:midtrans',
            'shipping_expedition' => 'required|in:jne_reg,jne_yes,jnt_regular,jnt_express,sicepat_regular,sicepat_halu,pos_regular,pos_express',
            'shipping_cost' => 'required|numeric|min:0',
            'cod_fee' => 'nullable|numeric|min:0',
            'save_address' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ]);
        
        Log::info('Validation passed');
        
        // Get cart items
        Log::info('Getting cart items for user: ' . (Auth::check() ? Auth::id() : 'guest'));
        
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            Log::info('Cart items count: ' . $cartItems->count());
            
            if ($cartItems->isEmpty()) {
                Log::info('Cart is empty, redirecting to cart');
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        } else {
            $sessionCart = Session::get('cart', []);
            Log::info('Session cart count: ' . count($sessionCart));
            
            if (empty($sessionCart)) {
                Log::info('Session cart is empty, redirecting to cart');
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
        $shippingCost = $request->shipping_cost;
        $codFee = $request->cod_fee ?? 0;
        
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
        
        $total = $subtotal - $discount + $shippingCost + $codFee;
        
        // Get shipping expedition details
        $expeditionDetails = $this->getExpeditionDetails($request->shipping_expedition);
        
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
                'cod_fee' => $codFee,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $shippingAddress,
                'shipping_expedition' => $request->shipping_expedition,
                'shipping_expedition_name' => $expeditionDetails['name'],
                'shipping_estimation' => $expeditionDetails['estimation'],
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
            
            DB::commit();
            
            // Clear cart and coupon only after successful order creation
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                Session::forget('cart');
            }
            
            // Clear coupon
            Session::forget('coupon_code');
            
            // Create Midtrans Snap Token
            try {
                Log::info('Creating Midtrans snap token for order: ' . $order->order_number);
                Log::info('Payment method: ' . $request->payment_method);
                Log::info('Order total: ' . $order->total_amount);
                
                $snapToken = $this->midtransService->createSnapToken($order);
                Log::info('Snap token created successfully: ' . $snapToken);
                
                // Store order in session 
                Session::put('completed_order', $order->id);
                
                // Return JSON response for AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'snap_token' => $snapToken,
                        'order_id' => $order->id,
                        'message' => 'Order created successfully'
                    ]);
                }
                
                // Fallback: Redirect to payment page
                return redirect()->route('checkout.payment', $order->id)
                    ->with('snap_token', $snapToken);
                    
            } catch (\Exception $e) {
                Log::error('Midtrans error during checkout: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                
                // If Midtrans fails, rollback the order creation
                DB::rollBack();
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment gateway error: ' . $e->getMessage()
                    ], 400);
                }
                
                return back()->withInput()->with('error', 'Payment gateway error: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing your order: ' . $e->getMessage()
                ], 500);
            }
            
            // Don't clear cart on error, so user can retry
            return back()->withInput()->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
        }
    }
    
    /**
     * Get expedition details by code.
     */
    private function getExpeditionDetails($expeditionCode)
    {
        $expeditions = [
            'jne_reg' => [
                'name' => 'JNE REG (Regular)',
                'estimation' => '2-3 hari kerja'
            ],
            'jne_yes' => [
                'name' => 'JNE YES (Yakin Esok Sampai)',
                'estimation' => '1-2 hari kerja'
            ],
            'jnt_regular' => [
                'name' => 'J&T Regular',
                'estimation' => '2-4 hari kerja'
            ],
            'jnt_express' => [
                'name' => 'J&T Express',
                'estimation' => '1-2 hari kerja'
            ],
            'sicepat_regular' => [
                'name' => 'SiCepat REG',
                'estimation' => '2-3 hari kerja'
            ],
            'sicepat_halu' => [
                'name' => 'SiCepat HALU (Hari Itu Sampai)',
                'estimation' => '1 hari kerja'
            ],
            'pos_regular' => [
                'name' => 'Pos Reguler',
                'estimation' => '3-5 hari kerja'
            ],
            'pos_express' => [
                'name' => 'Pos Kilat Khusus',
                'estimation' => '1-2 hari kerja'
            ],
        ];
        
        return $expeditions[$expeditionCode] ?? [
            'name' => 'Unknown Expedition',
            'estimation' => 'Unknown'
        ];
    }
    
    /**
     * Display Midtrans payment page
     */
    public function payment(Order $order)
    {
        // Check if user can access this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        $snapToken = session('snap_token');
        
        if (!$snapToken) {
            // Try to recreate snap token
            try {
                $snapToken = $this->midtransService->createSnapToken($order);
            } catch (\Exception $e) {
                Log::error('Failed to create snap token for order: ' . $order->order_number . ' - ' . $e->getMessage());
                return redirect()->route('checkout.index')
                    ->with('error', 'Payment gateway error: ' . $e->getMessage());
            }
        }

        return view('customer.checkout-payment', compact('order', 'snapToken'));
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
