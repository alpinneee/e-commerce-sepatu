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
     * Handle notification from Midtrans
     */
    public function notification(Request $request)
    {
        Log::info('Midtrans Notification Received', $request->all());

        try {
            $success = $this->midtransService->handleNotification();
            
            if ($success) {
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'error'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Handler Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment finish callback
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            
            if ($order) {
                // Store order in session for success page
                session(['completed_order' => $order->id]);
                
                return redirect()->route('checkout.success')->with('success', 'Payment process completed. Please check your order status.');
            }
        }
        
        return redirect()->route('checkout.success')->with('info', 'Payment process completed.');
    }

    /**
     * Handle payment error
     */
    public function error(Request $request)
    {
        return redirect()->route('checkout.index')
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Handle unfinished payment
     */
    public function unfinish(Request $request)
    {
        return redirect()->route('checkout.index')
            ->with('warning', 'Payment was not completed. Please complete your payment.');
    }

    /**
     * Get payment status for an order
     */
    public function paymentStatus(Order $order)
    {
        try {
            $status = $this->midtransService->getTransactionStatus($order->order_number);
            
            if ($status) {
                return response()->json([
                    'status' => 'success',
                    'data' => $status
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get payment status'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel payment for an order
     */
    public function cancelPayment(Order $order)
    {
        try {
            $result = $this->midtransService->cancelTransaction($order->order_number);
            
            if ($result) {
                $order->update([
                    'payment_status' => 'cancelled',
                    'status' => 'cancelled'
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment cancelled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to cancel payment'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
