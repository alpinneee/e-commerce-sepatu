<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get cart items count
     *
     * @return int
     */
    public function count()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = Session::get('cart', []);
            return collect($cart)->sum('quantity');
        }
    }

    /**
     * Get cart items
     *
     * @return \Illuminate\Support\Collection
     */
    public function items()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with('product')->get();
        } else {
            $cart = collect(Session::get('cart', []));
            
            if ($cart->isNotEmpty()) {
                $productIds = $cart->pluck('product_id')->toArray();
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
                
                return $cart->map(function ($item) use ($products) {
                    $item['product'] = $products[$item['product_id']] ?? null;
                    return $item;
                })->filter(function ($item) {
                    return $item['product'] !== null;
                });
            }
            
            return collect();
        }
    }

    /**
     * Get cart subtotal
     *
     * @return float
     */
    public function subtotal()
    {
        $items = $this->items();
        $subtotal = 0;
        
        foreach ($items as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        return $subtotal;
    }
} 