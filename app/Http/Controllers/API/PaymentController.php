<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    /**
     * Create a constructor.
     */
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }
    
    /**
     * Get Snap Token for an order.
     */
    public function getSnapToken(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);
        
        $order = Order::with(['user', 'items.product'])->findOrFail($request->order_id);
        
        // Check if order belongs to the authenticated user
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        // Parse shipping address
        $shippingAddress = json_decode($order->shipping_address, true);
        
        // Prepare transaction data for Midtrans
        $transactionDetails = [
            'order_id' => $order->order_number,
            'gross_amount' => (int) $order->total_amount,
        ];
        
        // Customer details
        $customerDetails = [
            'first_name' => $shippingAddress['name'] ?? $order->user->name ?? '',
            'email' => $shippingAddress['email'] ?? $order->user->email ?? '',
            'phone' => $shippingAddress['phone'] ?? $order->user->phone ?? '',
            'billing_address' => [
                'first_name' => $shippingAddress['name'] ?? $order->user->name ?? '',
                'phone' => $shippingAddress['phone'] ?? $order->user->phone ?? '',
                'address' => $shippingAddress['address'] ?? '',
                'city' => $shippingAddress['city'] ?? '',
                'postal_code' => $shippingAddress['postal_code'] ?? '',
                'country_code' => 'IDN',
            ],
            'shipping_address' => [
                'first_name' => $shippingAddress['name'] ?? $order->user->name ?? '',
                'phone' => $shippingAddress['phone'] ?? $order->user->phone ?? '',
                'address' => $shippingAddress['address'] ?? '',
                'city' => $shippingAddress['city'] ?? '',
                'postal_code' => $shippingAddress['postal_code'] ?? '',
                'country_code' => 'IDN',
            ],
        ];
        
        // Item details
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => $item->product->id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product->name, 0, 50),
            ];
        }
        
        // Add shipping cost as an item if applicable
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost',
            ];
        }
        
        // Add discount as an item if applicable
        if ($order->discount_amount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => (int) -$order->discount_amount,
                'quantity' => 1,
                'name' => 'Discount',
            ];
        }
        
        // Prepare transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ];
        
        try {
            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);
            
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment token: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle notification from Midtrans.
     */
    public function handleNotification(Request $request)
    {
        try {
            $notification = new Notification();
            
            $orderId = $notification->order_id;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;
            
            // Find the order
            $order = Order::where('order_number', $orderId)->firstOrFail();
            
            // Update order status based on transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->payment_status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $order->payment_status = 'paid';
                    $order->status = 'processing';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->payment_status = 'paid';
                $order->status = 'processing';
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $order->payment_status = 'failed';
            } else if ($transactionStatus == 'pending') {
                $order->payment_status = 'pending';
            }
            
            // Save payment details
            $order->payment_details = json_encode([
                'status_code' => $statusCode,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'time' => now(),
            ]);
            
            $order->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification processed successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Notification Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing notification: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle payment success callback.
     */
    public function handleSuccess(Request $request)
    {
        return redirect()->route('checkout.success')
            ->with('success', 'Payment completed successfully');
    }
    
    /**
     * Handle payment pending callback.
     */
    public function handlePending(Request $request)
    {
        return redirect()->route('profile.orders')
            ->with('info', 'Payment is pending. Please complete your payment.');
    }
    
    /**
     * Handle payment error callback.
     */
    public function handleError(Request $request)
    {
        return redirect()->route('profile.orders')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }
}
