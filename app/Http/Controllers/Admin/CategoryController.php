<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Generate slug from name
        $data['slug'] = Str::slug($request->name);
        
        // Set default value for is_active if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }
    
    /**
     * Redirect to index page with create form
     */
    public function create()
    {
        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified category
     */
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Generate slug from name
        $data['slug'] = Str::slug($request->name);
        
        // Set default value for is_active if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = false;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified category from storage
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if the category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
