<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    /**
     * Display a listing of the sales.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['servedBy', 'customer'])
            ->orderBy('created_at', 'desc');

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('sale_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('sale_date', '<=', $request->to_date);
        }

        if ($request->has('customer_name') && $request->customer_name) {
            $query->whereHas('customer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('apoteker.sales.history', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        // Ambil products dengan relationship category dan status tersedia
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->where('status', 'tersedia')
            ->orderBy('name')
            ->get();
            
        // Ambil customers dengan role 'pelanggan'
        $customers = User::where('role', 'pelanggan')
            ->orderBy('name')
            ->get();
            
        return view('apoteker.sales.create', compact('products', 'customers'));
    }

    /**
     * Display the POS interface.
     */
    public function pos()
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('stock', '>', 0)
                  ->where('status', 'tersedia');
        }])->get();
        
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->where('status', 'tersedia')
            ->get();
        
        return view('apoteker.sales.pos', compact('categories', 'products'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'payment_method' => 'required|string|in:cash,transfer,credit_card,debit_card,e_wallet,qris',
            'products' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'transaction_date' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            // Decode the products JSON
            $productsData = json_decode($request->products, true);
            
            // Debug log
            \Log::info('Products data received:', ['data' => $productsData]);
            
            if (empty($productsData) || !is_array($productsData)) {
                throw new \Exception('Data produk tidak valid atau kosong');
            }

            // Create a new sale
            $sale = new Sale();
            $sale->sale_number = 'SL-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(5));
            $sale->sale_date = $request->transaction_date ? Carbon::parse($request->transaction_date) : Carbon::now();
            $sale->customer_id = $request->customer_id;
            $sale->payment_method = $request->payment_method;
            $sale->notes = $request->notes;
            $sale->total_amount = 0; // Will be updated later
            $sale->served_by_user_id = Auth::id();
            $sale->status = 'completed';
            $sale->save();

            $totalAmount = 0;

            // Create sale details
            foreach ($productsData as $item) {
                // Validasi struktur data item
                if (!isset($item['id']) || !isset($item['quantity']) || !isset($item['price'])) {
                    throw new \Exception('Format data produk tidak valid');
                }

                $product = Product::find($item['id']);
                
                if (!$product) {
                    throw new \Exception('Produk dengan ID ' . $item['id'] . ' tidak ditemukan');
                }
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk ' . $product->name . '. Stok tersedia: ' . $product->stock);
                }

                $detail = new SaleDetail();
                $detail->sale_id = $sale->id;
                $detail->product_id = $product->id;
                $detail->quantity = $item['quantity'];
                $detail->unit_price = $item['price'];
                $detail->total_price = $detail->unit_price * $item['quantity'];
                $detail->save();

                // Update product stock and total sold
                $product->stock -= $item['quantity'];
                $product->total_sold += $item['quantity'];
                
                // Update status if stock is 0
                if ($product->stock <= 0) {
                    $product->status = 'habis';
                }
                
                $product->save();

                $totalAmount += $detail->total_price;
            }

            // Update the total amount
            $sale->total_amount = $totalAmount;
            $sale->save();

            DB::commit();
            
            return redirect()->route('apoteker.sales.show', $sale->id)
                ->with('success', 'Transaksi berhasil dibuat dengan nomor: ' . $sale->sale_number);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error creating sale: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));
            
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $sale->load(['details.product', 'servedBy', 'customer']);
        return view('apoteker.sales.show', compact('sale'));
    }

    /**
     * Print the sale receipt.
     */
    public function print(Sale $sale)
    {
        $sale->load(['details.product', 'servedBy', 'customer']);
        return view('apoteker.sales.print', compact('sale'));
    }

    /**
     * Process a refund for a sale.
     */
    public function refund(Request $request, Sale $sale)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*' => 'required|exists:sale_details,id',
        ]);

        DB::beginTransaction();
        try {
            $sale->status = 'refunded';
            $sale->notes = ($sale->notes ? $sale->notes . ' | ' : '') . 'REFUND: ' . $request->reason;
            $sale->save();

            foreach ($request->items as $detailId) {
                $detail = SaleDetail::find($detailId);
                if ($detail && $detail->sale_id == $sale->id) {
                    // Return the product to stock
                    if ($detail->product) {
                        $detail->product->stock += $detail->quantity;
                        $detail->product->total_sold -= $detail->quantity;
                        
                        // Update status if stock is available
                        if ($detail->product->stock > 0 && $detail->product->status == 'habis') {
                            $detail->product->status = 'tersedia';
                        }
                        
                        $detail->product->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('apoteker.sales.show', $sale->id)
                ->with('success', 'Pengembalian barang berhasil diproses');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get product details by ID.
     */
    public function getProductDetails($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => $product->category->name ?? 'N/A',
                'status' => $product->status
            ]
        ]);
    }

    /**
     * Search products by barcode.
     */
    public function getProductByBarcode($barcode)
    {
        // Asumsi ada field barcode di products table, jika tidak ada bisa search by ID
        $product = Product::with('category')
            ->where('barcode', $barcode)
            ->orWhere('id', $barcode)
            ->where('stock', '>', 0)
            ->where('status', 'tersedia')
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category->name ?? 'N/A'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    /**
     * Search products for modal.
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('search', '');
        
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->where('status', 'tersedia')
            ->where(function($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhereHas('category', function($q) use ($search) {
                              $q->where('name', 'like', '%' . $search . '%');
                          });
                }
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'N/A',
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'formatted_price' => 'Rp ' . number_format($product->price, 0, ',', '.')
                ];
            })
        ]);
    }
    
    /**
     * Display sales reports.
     */
    public function reports(Request $request)
    {
        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get sales data
        $sales = Sale::whereBetween('sale_date', [$start_date, $end_date])
            ->with(['saleDetails', 'servedBy'])
            ->orderBy('sale_date', 'desc')
            ->get();
            
        // Calculate summary statistics
        $total_revenue = $sales->sum('total_amount');
        $total_items_sold = $sales->sum(function ($sale) {
            return $sale->saleDetails->sum('quantity');
        });
        $total_transactions = $sales->count();
        
        return view('apoteker.reports.sales', compact('sales', 'total_revenue', 'total_items_sold', 'total_transactions', 'start_date', 'end_date'));
    }
}