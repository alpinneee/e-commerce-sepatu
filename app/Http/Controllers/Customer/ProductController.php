<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::active()->inStock()->with('category');
        
        // Apply category filter
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Apply price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->priceRange(
                $request->min_price ?? null,
                $request->max_price ?? null
            );
        }
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply sort
        $sortField = 'created_at';
        $sortDirection = 'desc';
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $sortField = 'price';
                    $sortDirection = 'asc';
                    break;
                case 'price_desc':
                    $sortField = 'price';
                    $sortDirection = 'desc';
                    break;
                case 'name_asc':
                    $sortField = 'name';
                    $sortDirection = 'asc';
                    break;
                case 'name_desc':
                    $sortField = 'name';
                    $sortDirection = 'desc';
                    break;
                case 'newest':
                    $sortField = 'created_at';
                    $sortDirection = 'desc';
                    break;
                case 'oldest':
                    $sortField = 'created_at';
                    $sortDirection = 'asc';
                    break;
            }
        }
        
        $query->orderBy($sortField, $sortDirection);
        
        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->ordered()->get();
        
        // Get min and max prices for filter
        $minPrice = Product::active()->min('price');
        $maxPrice = Product::active()->max('price');
        
        return view('customer.products.index', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }
    
    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category', 'images', 'reviews' => function($query) {
                $query->approved()->with('user')->latest();
            }])
            ->firstOrFail();
        
        // Get related products
        $relatedProducts = Product::active()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();
        
        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
    
    /**
     * Store a product review.
     */
    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);
        
        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
        
        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }
        
        // Create the review
        Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Require admin approval
        ]);
        
        return back()->with('success', 'Thank you for your review. It will be visible after approval.');
    }
    
    /**
     * Display products by category.
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        
        $products = Product::active()
            ->inStock()
            ->where('category_id', $category->id)
            ->paginate(12);
        
        return view('customer.products.category', compact('category', 'products'));
    }
    
    /**
     * Search for products.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255',
        ]);
        
        $query = $request->input('query');
        
        $products = Product::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12)
            ->withQueryString();
        
        return view('customer.products.search', compact('products', 'query'));
    }
    
    /**
     * Display all categories.
     */
    public function categories()
    {
        $categories = Category::active()->ordered()->withCount('activeProducts')->get();
        
        return view('customer.categories.index', compact('categories'));
    }
}
