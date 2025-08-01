<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    /**
     * Show customer profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('public.profile', compact('user'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $user = Auth::user();
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return back()->with([
                'success' => 'Password berhasil diperbarui!',
                'password_updated' => true
            ]);

        } catch (\Exception $e) {
            return back()->withErrors([
                'password' => 'Terjadi kesalahan saat memperbarui password. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Show customer sales history
     */
    public function sales(Request $request)
    {
        $query = Sale::with(['saleDetails.product', 'servedBy'])
            ->where('customer_id', Auth::id())
            ->orderBy('sale_date', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('public.transactions', compact('sales'));
    }

    /**
     * Show sale detail
     */
    public function saleDetail($id)
    {
        $sale = Sale::with(['saleDetails.product', 'servedBy', 'customer'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);

        return view('public.transaction-detail', compact('sale'));
    }

    /**
     * Show checkout page
     */
    public function checkout($productId)
    {
        $product = Product::with('supplier')->findOrFail($productId);

        if (!$product->isAvailable()) {
            return redirect()->route('home')->with('error', 'Produk tidak tersedia untuk dibeli.');
        }

        return view('public.checkout', compact('product'));
    }

    /**
     * Process sale transaction for single product
     */
    public function processSale(Request $request, $productId)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,transfer,credit_card,debit_card,e_wallet,qris'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $product = Product::findOrFail($productId);

        if (!$product->isAvailable() || $product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $unitPrice = $product->price;
            $quantity = $request->quantity;
            $totalPrice = $unitPrice * $quantity;

            // Create sale
            $sale = Sale::create([
                'customer_id' => Auth::id(),
                'served_by_user_id' => Auth::id(), // For online orders, customer serves themselves
                'total_amount' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => Sale::STATUS_COMPLETED,
                'sale_date' => now(),
                'notes' => $request->notes,
            ]);

            // Create sale detail (stock will be decreased automatically in SaleDetail model)
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);

            // Note: Stock is automatically decreased in SaleDetail model's boot method
            // No need to manually decrement here to avoid double decrease

            DB::commit();

            return redirect()->route('public.sale-detail', $sale->id)
                ->with('success', 'Transaksi berhasil! Terima kasih atas pembelian Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses transaksi. Silakan coba lagi.');
        }
    }
    
    /**
     * Process cart checkout
     */
    public function processCartCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:cash,transfer,credit_card,debit_card,e_wallet,qris'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('public.cart.index')->with('error', 'Keranjang belanja kosong.');
        }
        
        $productIds = array_column($cartItems, 'id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        // Verify stock availability for all products
        foreach ($cartItems as $item) {
            $product = $products[$item['id']] ?? null;
            
            if (!$product || !$product->isAvailable() || $product->stock < $item['quantity']) {
                return redirect()->route('public.cart.index')
                    ->with('error', "Stok produk {$product->name} tidak mencukupi.");
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate total amount
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $product = $products[$item['id']];
                $totalAmount += $product->price * $item['quantity'];
            }
            
            // Create sale
            $sale = Sale::create([
                'customer_id' => Auth::id(),
                'served_by_user_id' => Auth::id(), // For online orders, customer serves themselves
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => Sale::STATUS_COMPLETED,
                'sale_date' => now(),
                'notes' => $request->notes,
            ]);
            
            // Create sale details for each product
            foreach ($cartItems as $item) {
                $product = $products[$item['id']];
                $quantity = $item['quantity'];
                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $quantity;
                
                // Create sale detail (stock will be decreased automatically in SaleDetail model)
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
                
                // Note: Stock is automatically decreased in SaleDetail model's boot method
                // No need to manually decrement here to avoid double decrease
            }
            
            // Clear the cart after successful checkout
            Session::forget('cart');
            
            DB::commit();
            
            return redirect()->route('public.sale-detail', $sale->id)
                ->with('success', 'Transaksi berhasil! Terima kasih atas pembelian Anda.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses transaksi. Silakan coba lagi.');
        }
    }
} 