<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        try {
            // Get categories first to ensure they're available
            $categories = Category::orderBy('name')->get();
            
            $query = Product::with(['category', 'supplier']);
            
            // Apply filters if provided
            if ($request->filled('search')) {
                $query->where('name', 'like', "%{$request->search}%");
            }
            
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $products = $query->orderBy('name')->paginate(10);
            
            return view('admin.products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Admin ProductController@index: ' . $e->getMessage());
            
            // Create an empty paginator to prevent undefined variable errors
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // empty collection
                0, // total items
                10, // per page
                1, // current page
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('admin.products.index', [
                'products' => $products,
                'categories' => collect([])
            ])->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        try {
            $categories = Category::where('is_active', true)->orderBy('name')->get();
            $suppliers = Supplier::orderBy('name')->get();
            
            return view('admin.products.create', compact('categories', 'suppliers'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Admin ProductController@create: ' . $e->getMessage());
            
            // Return with empty collections to prevent undefined variable errors
            return view('admin.products.create', [
                'categories' => collect([]),
                'suppliers' => collect([])
            ])->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expired_date' => 'required|date|after:today',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }
        
        // Set status based on stock and expired date
        $data['status'] = 'tersedia';
        if ($data['stock'] <= 0) {
            $data['status'] = 'habis';
        } elseif (Carbon::parse($data['expired_date'])->isPast()) {
            $data['status'] = 'kadaluarsa';
        }
        
        // Set total_sold to 0 for new products
        $data['total_sold'] = 0;
        
        Product::create($data);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Obat berhasil ditambahkan.');
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        try {
            $product = Product::with(['category', 'supplier'])->findOrFail($id);
            
            return view('admin.products.show', compact('product'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Admin ProductController@show: ' . $e->getMessage());
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Obat tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            $categories = Category::where('is_active', true)->orderBy('name')->get();
            $suppliers = Supplier::orderBy('name')->get();
            
            return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Admin ProductController@edit: ' . $e->getMessage());
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Obat tidak ditemukan.');
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'stock' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'expired_date' => 'required|date',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);
            
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $imagePath = $request->file('image')->store('products', 'public');
                $data['image'] = $imagePath;
            }
            
            // Update status based on stock and expired date
            if ($data['stock'] <= 0) {
                $data['status'] = 'habis';
            } elseif (Carbon::parse($data['expired_date'])->isPast()) {
                $data['status'] = 'kadaluarsa';
            } else {
                $data['status'] = 'tersedia';
            }
            
            $product->update($data);
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Obat berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Admin ProductController@update: ' . $e->getMessage());
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui obat.');
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Check if product has related sale details
            if ($product->saleDetails()->count() > 0) {
                return redirect()->route('admin.products.index')
                    ->with('error', 'Obat tidak dapat dihapus karena sudah memiliki catatan penjualan.');
            }
            
            // Check if product has related purchase details
            if ($product->purchaseDetails()->count() > 0) {
                return redirect()->route('admin.products.index')
                    ->with('error', 'Obat tidak dapat dihapus karena sudah memiliki catatan pembelian.');
            }
            
            // Delete product image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $product->delete();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Obat berhasil dihapus.');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Admin ProductController@destroy: ' . $e->getMessage());
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Terjadi kesalahan saat menghapus obat: ' . $e->getMessage());
        }
    }

    /**
     * Search for products.
     */
    public function search(Request $request)
    {
        try {
            $categories = Category::where('is_active', true)->orderBy('name')->get();
            
            $query = Product::query();
            
            // Apply filters if provided
            if ($request->filled('keyword')) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
            
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }
            
            if ($request->filled('stock')) {
                switch ($request->stock) {
                    case 'available':
                        $query->where('stock', '>', 0);
                        break;
                    case 'low':
                        $query->where('stock', '>', 0)
                              ->where('stock', '<=', 10); // Assuming 10 is the threshold for low stock
                        break;
                    case 'out':
                        $query->where('stock', '<=', 0);
                        break;
                }
            }
            
            $products = $query->orderBy('name')->paginate(10);
            
            return view('admin.products.search', compact('categories', 'products'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Admin ProductController@search: ' . $e->getMessage());
            
            // Create an empty paginator to prevent undefined variable errors
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // empty collection
                0, // total items
                10, // per page
                1, // current page
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('admin.products.search', [
                'categories' => collect([]),
                'products' => $products
            ])->with('error', 'Terjadi kesalahan saat mencari produk.');
        }
    }
}
