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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:midtrans,cod',
            'shipping_expedition' => 'required|in:jne_reg,jne_yes,jnt_regular,jnt_express,sicepat_regular,sicepat_halu,pos_regular,pos_express',
            'shipping_cost' => 'required|numeric|min:0',
            'cod_fee' => 'nullable|numeric|min:0',
            'save_address' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
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
            
            // Clear cart
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                Session::forget('cart');
            }
            
            // Clear coupon
            Session::forget('coupon_code');
            
            DB::commit();
            
            // Create Midtrans Snap Token
            try {
                $snapToken = $this->midtransService->createSnapToken($order);
                
                // Store order in session 
                Session::put('completed_order', $order->id);
                
                // Redirect to payment page with snap token
                return redirect()->route('checkout.payment', $order->id)
                    ->with('snap_token', $snapToken);
                    
            } catch (\Exception $e) {
                // If Midtrans fails, redirect to manual payment instructions
                return redirect()->route('orders.payment-instructions', $order)
                    ->with('error', 'Payment gateway temporarily unavailable. Please use manual payment methods.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
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
                return redirect()->route('orders.payment-instructions', $order)
                    ->with('error', 'Payment gateway unavailable. Please use manual payment methods.');
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

    /**
     * Display payment instructions page.
     */
    public function paymentInstructions(Order $order)
    {
        // Check if user can access this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }
        
        return view('customer.payment-instructions', compact('order'));
    }

    /**
     * Upload payment proof.
     */
    public function uploadPaymentProof(Request $request, Order $order)
    {
        // Check if user can access this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Validate request
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Delete old payment proof if exists
            if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            // Store new payment proof
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Update order
            $order->update([
                'payment_proof' => $path,
                'payment_proof_uploaded_at' => now(),
                'notes' => $request->notes ? $order->notes . "\n\nBukti Pembayaran: " . $request->notes : $order->notes,
            ]);

            return redirect()->route('orders.payment-instructions', $order)
                ->with('success', 'Bukti pembayaran berhasil diupload. Admin akan segera memverifikasi pembayaran Anda.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
        }
    }
}
