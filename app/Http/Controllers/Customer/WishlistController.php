<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product.images', 'product.category')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('customer.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to wishlist.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if product is already in wishlist
        if (Wishlist::isInWishlist($userId, $productId)) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in your wishlist'
            ]);
        }

        // Add to wishlist
        Wishlist::addToWishlist($userId, $productId);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist successfully'
        ]);
    }

    /**
     * Remove a product from wishlist.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Remove from wishlist
        Wishlist::removeFromWishlist($userId, $productId);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist successfully'
            ]);
        }

        return back()->with('success', 'Product removed from wishlist successfully');
    }

    /**
     * Move wishlist item to cart.
     */
    public function moveToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $userId = Auth::id();

        // Get the product
        $product = Product::findOrFail($productId);

        // Add to cart (you can use your existing cart service here)
        // For now, we'll just redirect to the product page with add to cart parameter
        return redirect()->route('products.show', $product->slug)
            ->with('add_to_cart', true)
            ->with('quantity', $quantity);
    }

    /**
     * Clear all items from wishlist.
     */
    public function clear()
    {
        Wishlist::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Wishlist cleared successfully');
    }

    /**
     * Check if a product is in wishlist (for AJAX requests).
     */
    public function check(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $isInWishlist = Wishlist::isInWishlist(Auth::id(), $request->product_id);

        return response()->json([
            'in_wishlist' => $isInWishlist
        ]);
    }
}
