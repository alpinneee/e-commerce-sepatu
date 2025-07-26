<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        return view('customer.profile.index', compact('user'));
    }
    
    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        
        return back()->with('success', 'Profile updated successfully');
    }
    
    /**
     * Show the form for changing password.
     */
    public function showChangePasswordForm()
    {
        return view('customer.profile.change-password');
    }
    
    /**
     * Change the user's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return back()->with('success', 'Password changed successfully');
    }
    
    /**
     * Display the user's orders.
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.profile.orders', compact('orders'));
    }
    
    /**
     * Display a specific order.
     */
    public function showOrder(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load('items.product');
        
        return view('customer.profile.order-detail', compact('order'));
    }
    
    /**
     * Display the user's shipping addresses.
     */
    public function addresses()
    {
        $addresses = ShippingAddress::where('user_id', Auth::id())->get();
        return view('customer.profile.addresses', compact('addresses'));
    }
    
    /**
     * Store a new shipping address.
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);
        
        $address = new ShippingAddress([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'is_default' => $request->has('is_default'),
        ]);
        
        $address->save();
        
        // If set as default, update other addresses
        if ($request->has('is_default') && $request->is_default) {
            $address->setAsDefault();
        }
        
        return redirect()->route('profile.addresses')
            ->with('success', 'Shipping address added successfully');
    }
    
    /**
     * Update a shipping address.
     */
    public function updateAddress(Request $request, ShippingAddress $address)
    {
        // Check if address belongs to user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);
        
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->postal_code = $request->postal_code;
        $address->is_default = $request->has('is_default');
        $address->save();
        
        // If set as default, update other addresses
        if ($request->has('is_default') && $request->is_default) {
            $address->setAsDefault();
        }
        
        return redirect()->route('profile.addresses')
            ->with('success', 'Shipping address updated successfully');
    }
    
    /**
     * Delete a shipping address.
     */
    public function deleteAddress(ShippingAddress $address)
    {
        // Check if address belongs to user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $address->delete();
        
        return redirect()->route('profile.addresses')
            ->with('success', 'Shipping address deleted successfully');
    }
}
