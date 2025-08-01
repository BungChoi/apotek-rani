<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with product catalog
     */
    public function index(Request $request)
    {
        $query = Product::with(['supplier', 'category'])->available();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->bySupplier($request->supplier_id);
        }

        // Sort options
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('total_sold', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();

        return view('public.home', compact('products', 'suppliers', 'categories'));
    }

    /**
     * Show product detail
     */
    public function show($id)
    {
        $product = Product::with(['supplier', 'category'])->findOrFail($id);
        
        // Check if product is available for public viewing
        if (!$product->isAvailable()) {
            abort(404, 'Produk tidak tersedia');
        }

        // Get related products (same category, supplier, or similar price range)
        $relatedProducts = Product::available()
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id)
                      ->orWhere('supplier_id', $product->supplier_id)
                      ->orWhereBetween('price', [
                          $product->price * 0.8,
                          $product->price * 1.2
                      ]);
            })
            ->limit(4)
            ->get();

        return view('public.product-detail', compact('product', 'relatedProducts'));
    }
} 