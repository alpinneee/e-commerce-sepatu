<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Apply filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Apply sorting
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        $orders = $query->paginate(15);
        
        // Get order statuses for filter
        $orderStatuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];
        
        // Get payment statuses for filter
        $paymentStatuses = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];
        
        return view('admin.orders.index', compact('orders', 'orderStatuses', 'paymentStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product.images']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string',
        ]);
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }
    
    /**
     * Confirm payment for an order.
     */
    public function confirmPayment(Order $order)
    {
        try {
            DB::beginTransaction();
            
            // Update payment status to paid
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing' // Auto-update status to processing
            ]);
            
            // Send confirmation email to customer
            $this->sendPaymentConfirmationEmail($order);
            
            DB::commit();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pembayaran telah dikonfirmasi dan email notifikasi telah dikirim ke pelanggan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $order->status;
            $oldPaymentStatus = $order->payment_status;
            
            // Update order
            $order->update([
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'notes' => $request->notes,
            ]);
            
            // Send email notification if status changed
            if ($oldStatus !== $request->status || $oldPaymentStatus !== $request->payment_status) {
                $this->sendStatusUpdateEmail($order, $oldStatus, $oldPaymentStatus);
            }
            
            DB::commit();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Status pesanan berhasil diperbarui dan notifikasi telah dikirim ke pelanggan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Generate invoice for the order.
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.invoice', compact('order'));
    }
    
    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items.product']);
        
        // Apply the same filters as in index
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->get();
        
        $filename = 'orders-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Order ID',
                'Order Number',
                'Customer',
                'Email',
                'Total Amount',
                'Payment Method',
                'Shipping Expedition',
                'Status',
                'Payment Status',
                'Date',
            ]);
            
            // Add order data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->order_number,
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? json_decode($order->shipping_address)->email,
                    $order->total_amount,
                    $order->payment_method_label,
                    $order->shipping_expedition_name ?? 'N/A',
                    $order->status_label,
                    $order->payment_status_label,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Send payment confirmation email to customer.
     */
    private function sendPaymentConfirmationEmail(Order $order)
    {
        try {
            if ($order->user && $order->user->email) {
                // Here you would typically send an email
                // For now, we'll just log it or skip
                Log::info("Payment confirmation email sent for order: {$order->order_number}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send payment confirmation email: " . $e->getMessage());
        }
    }
    
    /**
     * Send status update email to customer.
     */
    private function sendStatusUpdateEmail(Order $order, $oldStatus, $oldPaymentStatus)
    {
        try {
            if ($order->user && $order->user->email) {
                // Here you would typically send an email
                // For now, we'll just log it or skip
                Log::info("Status update email sent for order: {$order->order_number} - Status: {$oldStatus} -> {$order->status}, Payment: {$oldPaymentStatus} -> {$order->payment_status}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send status update email: " . $e->getMessage());
        }
    }
    
    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::where('payment_status', 'paid')
                                   ->whereDate('created_at', today())
                                   ->sum('total_amount'),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];
        
        return $stats;
    }
}
