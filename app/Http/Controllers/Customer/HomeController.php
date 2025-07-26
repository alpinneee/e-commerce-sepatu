<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::active()
            ->inStock()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        // Get products on sale
        $saleProducts = Product::active()
            ->inStock()
            ->onSale()
            ->with('category')
            ->orderBy('discount_price', 'asc')
            ->take(8)
            ->get();
        
        // Get product categories
        $categories = Category::active()
            ->ordered()
            ->take(6)
            ->get();
        
        return view('customer.home', compact('featuredProducts', 'saleProducts', 'categories'));
    }
    
    /**
     * Display the about page.
     */
    public function about()
    {
        return view('customer.about');
    }
    
    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('customer.contact');
    }
    
    /**
     * Process contact form submission.
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Here you would typically send an email or save to database
        // For now, we'll just redirect with a success message
        
        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
    
    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        return view('customer.faq');
    }
}
