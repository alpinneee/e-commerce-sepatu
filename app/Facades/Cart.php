<?php

namespace App\Facades;

use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Session;

class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }

    /**
     * Get cart items count
     *
     * @return int
     */
    public static function count()
    {
        if (Auth::check()) {
            return CartModel::where('user_id', Auth::id())->sum('quantity');
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
    public static function items()
    {
        if (Auth::check()) {
            return CartModel::where('user_id', Auth::id())->with('product')->get();
        } else {
            $cart = collect(Session::get('cart', []));
            
            if ($cart->isNotEmpty()) {
                $productIds = $cart->pluck('product_id')->toArray();
                $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
                
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
    public static function subtotal()
    {
        $items = self::items();
        $subtotal = 0;
        
        foreach ($items as $item) {
            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        return $subtotal;
    }
} 