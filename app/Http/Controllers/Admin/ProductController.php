<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Apply filters
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status == 'active');
        }
        
        // Apply sorting
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        $products = $query->paginate(10);
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'required|string|max:100|unique:products',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create slug
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            
            // Create product
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'sku' => $request->sku,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'is_active' => $request->has('is_active'),
                'meta_title' => $request->meta_title ?? $request->name,
                'meta_description' => $request->meta_description,
            ]);
            
            // Handle images
            if ($request->hasFile('images')) {
                $isPrimary = true;
                
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'alt_text' => $request->name,
                        'is_primary' => $isPrimary,
                        'sort_order' => $index,
                    ]);
                    
                    $isPrimary = false;
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'An error occurred while creating the product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'images');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('category', 'images');
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update slug if name changed
            if ($product->name != $request->name) {
                $slug = Str::slug($request->name);
                $originalSlug = $slug;
                $count = 1;
                
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }
                
                $product->slug = $slug;
            }
            
            // Update product
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'sku' => $request->sku,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'is_active' => $request->has('is_active'),
                'meta_title' => $request->meta_title ?? $request->name,
                'meta_description' => $request->meta_description,
            ]);
            
            // Handle new images
            if ($request->hasFile('images')) {
                $currentImagesCount = $product->images->count();
                
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'alt_text' => $request->name,
                        'is_primary' => ($currentImagesCount == 0 && $index == 0),
                        'sort_order' => $currentImagesCount + $index,
                    ]);
                }
            }
            
            // Handle deleted images
            if ($request->has('deleted_images')) {
                $imagesToDelete = ProductImage::whereIn('id', $request->deleted_images)
                    ->where('product_id', $product->id)
                    ->get();
                
                foreach ($imagesToDelete as $image) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
                
                // If the primary image was deleted, set a new one
                if (!$product->images()->where('is_primary', true)->exists()) {
                    $firstImage = $product->images()->first();
                    if ($firstImage) {
                        $firstImage->update(['is_primary' => true]);
                    }
                }
            }
            
            // Update primary image
            if ($request->has('primary_image')) {
                $product->images()->update(['is_primary' => false]);
                
                ProductImage::where('id', $request->primary_image)
                    ->where('product_id', $product->id)
                    ->update(['is_primary' => true]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'An error occurred while updating the product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        
        try {
            // Delete product images from storage
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            
            // Delete product and related records (images will be deleted via cascade)
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'An error occurred while deleting the product: ' . $e->getMessage());
        }
    }
}
