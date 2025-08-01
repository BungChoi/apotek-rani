<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $products = [];
        $total = 0;
        
        if (!empty($cartItems)) {
            // Get all products in the cart
            $productIds = array_column($cartItems, 'id');
            $productItems = Product::whereIn('id', $productIds)->get();
            
            // Map products with quantities
            foreach ($productItems as $product) {
                $quantity = $cartItems[array_search($product->id, array_column($cartItems, 'id'))]['quantity'];
                $subtotal = $product->price * $quantity;
                $total += $subtotal;
                
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        return view('public.cart', compact('products', 'total'));
    }
    
    /**
     * Add product to cart
     */
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        if (!$product->isAvailable()) {
            return back()->with('error', 'Produk tidak tersedia untuk dibeli.');
        }
        
        $cart = Session::get('cart', []);
        $quantity = $request->input('quantity', 1);
        
        // Check if product is already in cart
        $productIndex = array_search($productId, array_column($cart, 'id'));
        
        if ($productIndex !== false) {
            // Update quantity if product is already in cart
            $cart[$productIndex]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $cart[] = [
                'id' => $productId,
                'quantity' => $quantity,
            ];
        }
        
        Session::put('cart', $cart);
        
        return redirect()->route('public.cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
    
    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);
            
            $productId = $request->product_id;
            $quantity = $request->quantity;
        
        // Check if product exists and has enough stock
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan']);
        }
        
        if ($quantity > $product->stock) {
            return response()->json(['success' => false, 'message' => "Stok tidak mencukupi. Stok tersedia: {$product->stock}"]);
        }
        
        $cart = Session::get('cart', []);
        
        $productIndex = array_search($productId, array_column($cart, 'id'));
        
        if ($productIndex !== false) {
            $cart[$productIndex]['quantity'] = $quantity;
            Session::put('cart', $cart);
            
            // Calculate new total for response
            $total = 0;
            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $total += $product->price * $item['quantity'];
                }
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Keranjang berhasil diperbarui',
                'total' => $total,
                'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.')
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan di keranjang']);
        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
        }
    }
    
    /**
     * Remove item from cart
     */
    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);
        
        $productIndex = array_search($productId, array_column($cart, 'id'));
        
        if ($productIndex !== false) {
            array_splice($cart, $productIndex, 1);
            Session::put('cart', $cart);
            return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
        }
        
        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }
    
    /**
     * Clear all items from cart
     */
    public function clearCart()
    {
        Session::forget('cart');
        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
    
    /**
     * Show checkout page with all cart items
     */
    public function checkout()
    {
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('public.cart.index')->with('error', 'Keranjang belanja kosong.');
        }
        
        $productIds = array_column($cartItems, 'id');
        $products = Product::whereIn('id', $productIds)->get();
        $total = 0;
        
        foreach ($products as $product) {
            $quantity = $cartItems[array_search($product->id, array_column($cartItems, 'id'))]['quantity'];
            
            if ($product->stock < $quantity) {
                return redirect()->route('public.cart.index')
                    ->with('error', "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}");
            }
            
            $total += $product->price * $quantity;
        }
        
        return view('public.cart-checkout', compact('products', 'cartItems', 'total'));
    }
}