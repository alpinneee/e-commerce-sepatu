<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function notification(Request $request)
    {
        Log::info('Midtrans notification received', $request->all());
        
        try {
            $result = $this->midtransService->handleNotification();
            
            if ($result) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle successful payment redirect
     */
    public function finish(Request $request)
    {
        Log::info('Midtrans finish callback', $request->all());
        
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            
            if ($order) {
                // Store order in session for success page
                session(['completed_order' => $order->id]);
                
                return redirect()->route('checkout.success')
                    ->with('success', 'Payment completed successfully!');
            }
        }
        
        // If no specific order, try to get from session
        $completedOrderId = session('completed_order');
        if ($completedOrderId) {
            return redirect()->route('checkout.success')
                ->with('success', 'Payment completed successfully!');
        }
        
        return redirect()->route('home')
            ->with('info', 'Payment completed');
    }

    /**
     * Handle unfinished payment redirect
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->get('order_id');
        
        Log::info('Unfinished payment for order: ' . $orderId);
        
        return redirect()->route('profile.orders')
            ->with('warning', 'Payment was not completed. You can continue the payment from your order history.');
    }

    /**
     * Handle payment error redirect
     */
    public function error(Request $request)
    {
        $orderId = $request->get('order_id');
        
        Log::error('Payment error for order: ' . $orderId);
        
        return redirect()->route('profile.orders')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }
}