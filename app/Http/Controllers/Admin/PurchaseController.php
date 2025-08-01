<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{  
    public function history()
    {
        $purchases = Purchase::with(['supplier', 'createdBy', 'purchaseDetails'])
            ->orderBy('created_at', 'desc');
        
        // Filter by search
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $purchases->where('purchase_number', 'like', "%{$search}%");
        }
        
        // Filter by supplier
        if (request()->has('supplier_id') && !empty(request('supplier_id'))) {
            $purchases->where('supplier_id', request('supplier_id'));
        }
        
        // Filter by status
        if (request()->has('status') && !empty(request('status'))) {
            $purchases->where('status', request('status'));
        }
        
        // Filter by date range
        if (request()->has('date_range') && !empty(request('date_range'))) {
            $dates = explode(' - ', request('date_range'));
            if (count($dates) == 2) {
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                $purchases->whereBetween('purchase_date', [$startDate, $endDate]);
            }
        }
        
        $suppliers = Supplier::orderBy('name')->get();
        $purchases = $purchases->paginate(15)->withQueryString();
        
        return view('admin.purchases.history', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        
        try {
            // Load products with their relationships for better display
            $products = Product::with(['category', 'supplier'])
                ->orderBy('name')
                ->get();
            
            // Log for debugging
            \Log::info('Products loaded: ' . $products->count());
            
            // Debug
            if ($products->isEmpty()) {
                // If products is empty, fetch some sample products to display
                $products = collect([
                    (object)[
                        'id' => 1,
                        'name' => 'Sample Product 1',
                        'price' => 50000,
                        'stock' => 100,
                        'category' => (object)['name' => 'Sample Category'],
                        'supplier' => (object)['name' => 'Sample Supplier']
                    ],
                    (object)[
                        'id' => 2,
                        'name' => 'Sample Product 2',
                        'price' => 75000,
                        'stock' => 50,
                        'category' => (object)['name' => 'Sample Category'],
                        'supplier' => (object)['name' => 'Sample Supplier']
                    ],
                    (object)[
                        'id' => 3,
                        'name' => 'Sample Product 3',
                        'price' => 25000,
                        'stock' => 10,
                        'category' => (object)['name' => 'Sample Category 2'],
                        'supplier' => (object)['name' => 'Another Supplier']
                    ]
                ]);
                
                \Log::warning('Using sample products since no products found in database');
            }
        } catch (\Exception $e) {
            \Log::error('Error loading products: ' . $e->getMessage());
            
            // Provide sample products in case of error
            $products = collect([
                (object)[
                    'id' => 1,
                    'name' => 'Sample Product 1 (Error fallback)',
                    'price' => 50000,
                    'stock' => 100,
                    'category' => (object)['name' => 'Sample Category'],
                    'supplier' => (object)['name' => 'Sample Supplier']
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Sample Product 2 (Error fallback)',
                    'price' => 75000,
                    'stock' => 50,
                    'category' => (object)['name' => 'Sample Category'],
                    'supplier' => (object)['name' => 'Sample Supplier']
                ]
            ]);
        }
        
        $paymentMethods = [
            Purchase::PAYMENT_CASH => 'Cash',
            Purchase::PAYMENT_TRANSFER => 'Transfer',
            Purchase::PAYMENT_CREDIT => 'Credit',
            Purchase::PAYMENT_CHECK => 'Check'
        ];
        
        return view('admin.purchases.create', compact('suppliers', 'products', 'paymentMethods'));
    }

    public function store(Request $request)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'purchase_date' => 'required|date',
        'payment_method' => 'required|in:' . implode(',', [
            Purchase::PAYMENT_CASH,
            Purchase::PAYMENT_TRANSFER,
            Purchase::PAYMENT_CREDIT,
            Purchase::PAYMENT_CHECK,
        ]),
        'notes' => 'nullable|string|max:255',
        'product_id' => 'required|array|min:1',
        'product_id.*' => 'exists:products,id',
        'quantity' => 'required|array|min:1',
        'quantity.*' => 'numeric|min:1',
        'unit_cost' => 'required|array|min:1',
        'unit_cost.*' => 'required|numeric|min:0',
        'auto_receive' => 'nullable|boolean', // Tambahan untuk auto receive
    ], [
        'product_id.required' => 'Minimal pilih satu produk untuk pembelian.',
        'product_id.min' => 'Minimal pilih satu produk untuk pembelian.',
        'quantity.required' => 'Quantity produk harus diisi.',
        'unit_cost.required' => 'Harga beli produk harus diisi.',
    ]);

    DB::beginTransaction();
    
    try {
        // Calculate total amount first
        $totalAmount = 0;
        
        // Validate and calculate total before creating purchase
        foreach ($request->product_id as $key => $productId) {
            if (empty($productId)) continue;
            
            $quantity = $request->quantity[$key] ?? 0;
            $unitCost = $request->unit_cost[$key] ?? 0;
            
            if ($quantity <= 0 || $unitCost < 0) {
                throw new \Exception("Quantity dan harga beli harus valid untuk semua produk.");
            }
            
            $totalAmount += $quantity * $unitCost;
        }
        
        // Determine initial status based on auto_receive option
        $initialStatus = Purchase::STATUS_COMPLETED; // Always set as completed to update stock
        
        // Create purchase with total_amount included
        $purchase = new Purchase();
        $purchase->supplier_id = $request->supplier_id;
        $purchase->created_by_user_id = Auth::id();
        $purchase->purchase_date = $request->purchase_date;
        $purchase->payment_method = $request->payment_method;
        $purchase->status = $initialStatus;
        $purchase->notes = $request->notes;
        $purchase->total_amount = $totalAmount;
        $purchase->save();
        
        // Create purchase details and update stock if auto-received
        foreach ($request->product_id as $key => $productId) {
            if (empty($productId)) continue;
            
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$key];
            $unitCost = $request->unit_cost[$key];
            
            if (empty($unitCost) || $unitCost <= 0) {
                $unitCost = $product->price;
            }
            
            $purchaseDetail = new PurchaseDetail();
            $purchaseDetail->purchase_id = $purchase->id;
            $purchaseDetail->product_id = $productId;
            $purchaseDetail->product_name_snapshot = $product->name;
            $purchaseDetail->quantity_ordered = $quantity;
            
            // Always set quantity_received and update stock
            $purchaseDetail->quantity_received = $quantity;
            $purchaseDetail->received_date = now();
            
            // Update product stock
            $oldStock = $product->stock;
            $product->stock += $quantity;
            
            // Update product status based on new stock
            if ($product->stock > 0 && $product->status === 'habis' && !Carbon::parse($product->expired_date)->isPast()) {
                $product->status = 'tersedia';
            } else {
                // Re-evaluate status using the model's method
                $product->updateStatus();
            }
            
            $product->save();
            
            // Log stock movement
            \Log::info("Stock updated for product {$product->name}: {$oldStock} + {$quantity} = {$product->stock} (Purchase #{$purchase->purchase_number})");
            
            $purchaseDetail->unit_cost = $unitCost;
            $purchaseDetail->total_cost = $quantity * $unitCost;
            $purchaseDetail->save();
        }
        
        DB::commit();
        
        $message = 'Pembelian berhasil dibuat dan stok otomatis ditambahkan dengan nomor: ' . $purchase->purchase_number;
        
        return redirect()->route('admin.purchases.history')
            ->with('success', $message);
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Purchase creation failed: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['_token'])
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

/**
 * Process receiving goods (this updates stock)
 */
public function processReceive(Request $request, $id)
{
    $purchase = Purchase::with('purchaseDetails.product')
        ->findOrFail($id);
    
    if (!in_array($purchase->status, [Purchase::STATUS_ORDERED, Purchase::STATUS_RECEIVED])) {
        return redirect()->route('admin.purchases.show', $id)
            ->with('error', 'Status pembelian tidak valid untuk penerimaan barang.');
    }
    
    $request->validate([
        'receive_detail_id' => 'required|array',
        'receive_detail_id.*' => 'exists:purchase_details,id',
        'receive_quantity' => 'required|array',
        'receive_quantity.*' => 'numeric|min:0',
        'batch_number' => 'nullable|array',
        'expiry_date' => 'nullable|array',
        'expiry_date.*' => 'nullable|date',
    ]);

    DB::beginTransaction();
    
    try {
        $isFullyReceived = true;
        $stockUpdates = []; // Track stock updates for logging
        
        foreach ($request->receive_detail_id as $key => $detailId) {
            $detail = PurchaseDetail::with('product')->findOrFail($detailId);
            
            // Get new quantity to receive
            $newQuantityReceived = $request->receive_quantity[$key] ?? 0;
            
            // Only process if there's a new quantity to receive
            if ($newQuantityReceived > 0) {
                $totalReceived = $detail->quantity_received + $newQuantityReceived;
                
                // Make sure we don't receive more than ordered
                if ($totalReceived > $detail->quantity_ordered) {
                    throw new \Exception("Jumlah yang diterima tidak boleh melebihi jumlah yang dipesan untuk {$detail->product_name_snapshot}");
                }
                
                // Update purchase detail
                $detail->quantity_received = $totalReceived;
                
                // Update batch number and expiry date if provided
                if (isset($request->batch_number[$key]) && !empty($request->batch_number[$key])) {
                    $detail->batch_number = $request->batch_number[$key];
                }
                
                if (isset($request->expiry_date[$key]) && !empty($request->expiry_date[$key])) {
                    $detail->expiry_date = $request->expiry_date[$key];
                }
                
                $detail->received_date = now();
                $detail->save();
                
                // UPDATE PRODUCT STOCK - This is the key part!
                $product = $detail->product;
                $oldStock = $product->stock;
                $product->stock += $newQuantityReceived;
                
                // Update product status based on new stock
                if ($product->stock > 0 && $product->status === 'habis' && !Carbon::parse($product->expired_date)->isPast()) {
                    $product->status = 'tersedia';
                } else {
                    // Re-evaluate status using the model's method
                    $product->updateStatus();
                }
                
                $product->save();
                
                // Track for logging
                $stockUpdates[] = [
                    'product' => $product->name,
                    'old_stock' => $oldStock,
                    'new_stock' => $product->stock,
                    'added' => $newQuantityReceived
                ];
                
                \Log::info("Stock updated for product {$product->name}: {$oldStock} + {$newQuantityReceived} = {$product->stock}");
            }
            
            // Check if this detail is fully received
            if ($detail->quantity_received < $detail->quantity_ordered) {
                $isFullyReceived = false;
            }
        }
        
        // Update purchase status
        if ($isFullyReceived) {
            $purchase->status = Purchase::STATUS_COMPLETED;
        } else {
            $purchase->status = Purchase::STATUS_RECEIVED;
        }
        
        $purchase->save();
        
        DB::commit();
        
        // Prepare success message with stock updates info
        $message = 'Penerimaan barang berhasil diproses.';
        if (!empty($stockUpdates)) {
            $message .= ' Stok yang diperbarui: ';
            $updates = [];
            foreach ($stockUpdates as $update) {
                $updates[] = "{$update['product']} (+{$update['added']})";
            }
            $message .= implode(', ', $updates);
        }
        
        return redirect()->route('admin.purchases.show', $purchase->id)
            ->with('success', $message);
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Purchase receive failed: ' . $e->getMessage(), [
            'purchase_id' => $id,
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'purchaseDetails.product'])
            ->findOrFail($id);
        
        return view('admin.purchases.show', compact('purchase'));
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);
        
        // Only allow editing draft or ordered purchases
        if (!($purchase->isDraft() || $purchase->status === Purchase::STATUS_ORDERED)) {
            return redirect()->route('admin.purchases.show', $id)
                ->with('error', 'Hanya pembelian dengan status draft atau ordered yang dapat diedit.');
        }
        
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'payment_method' => 'required|in:' . implode(',', [
                Purchase::PAYMENT_CASH,
                Purchase::PAYMENT_TRANSFER,
                Purchase::PAYMENT_CREDIT,
                Purchase::PAYMENT_CHECK,
            ]),
            'notes' => 'nullable|string|max:255',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'unit_cost' => 'required|array',
            'unit_cost.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $purchase->supplier_id = $request->supplier_id;
            $purchase->purchase_date = $request->purchase_date;
            $purchase->payment_method = $request->payment_method;
            $purchase->notes = $request->notes;
            // Keep the original status
            $purchase->save();
            
            // Delete existing purchase details
            $purchase->purchaseDetails()->delete();
            
            $totalAmount = 0;
            
            // Create new purchase details
            foreach ($request->product_id as $key => $productId) {
                if (empty($productId)) continue;
                
                $product = Product::findOrFail($productId);
                
                // Ensure unit_cost is properly set
                $unitCost = $request->unit_cost[$key];
                if (empty($unitCost) || $unitCost <= 0) {
                    $unitCost = $product->price; // Use product price if unit cost is not set
                }
                
                $purchaseDetail = new PurchaseDetail();
                $purchaseDetail->purchase_id = $purchase->id;
                $purchaseDetail->product_id = $productId;
                $purchaseDetail->product_name_snapshot = $product->name;
                $purchaseDetail->quantity_ordered = $request->quantity[$key];
                $purchaseDetail->quantity_received = 0; // Reset quantity received since we're re-creating the details
                $purchaseDetail->unit_cost = $unitCost;
                $purchaseDetail->total_cost = $request->quantity[$key] * $unitCost;
                $purchaseDetail->save();
                
                $totalAmount += $purchaseDetail->total_cost;
            }
            
            $purchase->total_amount = $totalAmount;
            $purchase->save();
            
            DB::commit();
            
            return redirect()->route('admin.purchases.show', $purchase->id)
                ->with('success', 'Pesanan pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function receive($id)
    {
        $purchase = Purchase::with(['supplier', 'purchaseDetails.product'])
            ->findOrFail($id);
        
        if (!in_array($purchase->status, [Purchase::STATUS_ORDERED, Purchase::STATUS_RECEIVED])) {
            return redirect()->route('admin.purchases.show', $id)
                ->with('error', 'Status pembelian tidak valid untuk penerimaan barang.');
        }
        
        return view('admin.purchases.receive', compact('purchase'));
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // Delete purchase details first
            $purchase->purchaseDetails()->delete();
            
            // Then delete the purchase
            $purchase->delete();
            
            DB::commit();
            
            return redirect()->route('admin.purchases.history')
                ->with('success', 'Pesanan pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getProductDetails($id)
    {
        $product = Product::with(['category', 'supplier'])->findOrFail($id);
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'formatted_price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
            'stock' => $product->stock,
            'category' => $product->category ? $product->category->name : null,
            'supplier' => $product->supplier ? $product->supplier->name : null,
            'status' => $product->status,
        ]);
    }
}
