<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Store a newly created supplier in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'required|email|max:255|unique:suppliers',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }
    
    /**
     * Show the form for creating a new supplier
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Display the specified supplier
     */
    public function show($id)
    {
        $supplier = Supplier::with('products')->findOrFail($id);
        
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'required|email|max:255|unique:suppliers,email,' . $id,
        ]);

        $supplier->update($request->all());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    /**
     * Remove the specified supplier from storage
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Check if supplier has related products
        if($supplier->products()->count() > 0) {
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena masih memiliki produk terkait');
        }
        
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}
