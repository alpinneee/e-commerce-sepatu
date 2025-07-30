<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap token for payment
     */
    public function createSnapToken(Order $order)
    {
        $params = $this->buildTransactionParams($order);
        
        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Store Midtrans transaction details
            $order->update([
                'payment_details' => [
                    'snap_token' => $snapToken,
                    'transaction_id' => $params['transaction_details']['order_id'],
                    'created_at' => now()->toISOString()
                ]
            ]);
            
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            throw new \Exception('Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Build transaction parameters for Midtrans
     */
    private function buildTransactionParams(Order $order)
    {
        $shippingAddress = $order->shipping_address_object;
        
        // Build item details
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => $item->product->id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
                'brand' => 'Toko Sepatu',
                'category' => $item->product->category->name ?? 'Shoes',
                'merchant_name' => 'Toko Sepatu'
            ];
        }
        
        // Add shipping cost as item
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'shipping',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost - ' . ($order->shipping_expedition_name ?? 'Standard'),
                'category' => 'Shipping'
            ];
        }
        
        // Add COD fee if applicable
        if ($order->cod_fee > 0) {
            $itemDetails[] = [
                'id' => 'cod_fee',
                'price' => (int) $order->cod_fee,
                'quantity' => 1,
                'name' => 'COD Fee',
                'category' => 'Fee'
            ];
        }
        
        // Add discount as negative item
        if ($order->discount_amount > 0) {
            $itemDetails[] = [
                'id' => 'discount',
                'price' => -(int) $order->discount_amount,
                'quantity' => 1,
                'name' => 'Discount',
                'category' => 'Discount'
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $shippingAddress->name,
                'email' => $shippingAddress->email,
                'phone' => $shippingAddress->phone,
                'billing_address' => [
                    'first_name' => $shippingAddress->name,
                    'email' => $shippingAddress->email,
                    'phone' => $shippingAddress->phone,
                    'address' => $shippingAddress->address,
                    'city' => $shippingAddress->city,
                    'postal_code' => $shippingAddress->postal_code,
                    'country_code' => 'IDN'
                ],
                'shipping_address' => [
                    'first_name' => $shippingAddress->name,
                    'email' => $shippingAddress->email,
                    'phone' => $shippingAddress->phone,
                    'address' => $shippingAddress->address,
                    'city' => $shippingAddress->city,
                    'postal_code' => $shippingAddress->postal_code,
                    'country_code' => 'IDN'
                ]
            ],
            'enabled_payments' => config('midtrans.enable_payments'),
            'callbacks' => [
                'finish' => config('midtrans.finish_url') . '?order_id=' . $order->order_number,
            ]
        ];

        return $params;
    }

    /**
     * Handle notification from Midtrans
     */
    public function handleNotification()
    {
        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;
            
            // Find order by order number
            $order = Order::where('order_number', $orderId)->first();
            
            if (!$order) {
                Log::error('Order not found for Midtrans notification: ' . $orderId);
                return false;
            }

            Log::info('Midtrans Notification: ' . $orderId . ' - Status: ' . $transactionStatus);

            // Update order based on transaction status
            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $this->updateOrderPaymentStatus($order, 'pending', 'Payment challenged, please take action');
                    } else if ($fraudStatus == 'accept') {
                        $this->updateOrderPaymentStatus($order, 'paid', 'Payment successful');
                    }
                    break;
                    
                case 'settlement':
                    $this->updateOrderPaymentStatus($order, 'paid', 'Payment settled');
                    break;
                    
                case 'pending':
                    $this->updateOrderPaymentStatus($order, 'pending', 'Payment pending');
                    break;
                    
                case 'deny':
                    $this->updateOrderPaymentStatus($order, 'failed', 'Payment denied');
                    break;
                    
                case 'cancel':
                case 'expire':
                    $this->updateOrderPaymentStatus($order, 'failed', 'Payment cancelled/expired');
                    break;
                    
                case 'refund':
                case 'partial_refund':
                    $this->updateOrderPaymentStatus($order, 'refunded', 'Payment refunded');
                    break;
                    
                default:
                    Log::warning('Unknown Midtrans transaction status: ' . $transactionStatus);
                    break;
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update order payment status
     */
    private function updateOrderPaymentStatus(Order $order, string $paymentStatus, string $note)
    {
        $order->update([
            'payment_status' => $paymentStatus,
            'notes' => $order->notes . "\n\nMidtrans: " . $note . ' at ' . now()->format('Y-m-d H:i:s')
        ]);

        // Auto update order status for successful payments
        if ($paymentStatus === 'paid' && $order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }
        
        Log::info("Order {$order->order_number} payment status updated to: {$paymentStatus}");
    }

    /**
     * Get transaction status from Midtrans
     */
    public function getTransactionStatus(string $orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Failed to get Midtrans transaction status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction(string $orderId)
    {
        try {
            $cancel = Transaction::cancel($orderId);
            return $cancel;
        } catch (\Exception $e) {
            Log::error('Failed to cancel Midtrans transaction: ' . $e->getMessage());
            return null;
        }
    }
} 