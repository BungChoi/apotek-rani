<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get unique customers from sales
        $customers = Sale::select('customer_name', 'customer_id', 'customer_phone')
            ->distinct('customer_id')
            ->orderBy('customer_name')
            ->paginate(15);
            
        return view('apoteker.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get customer information from sales
        $customer = Sale::where('customer_id', $id)->first();
        
        if (!$customer) {
            return redirect()->route('apoteker.customers.index')
                ->with('error', 'Pelanggan tidak ditemukan');
        }
        
        // Get purchase history
        $purchaseHistory = Sale::where('customer_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate stats
        $totalSpent = $purchaseHistory->sum('total_amount');
        $purchaseCount = $purchaseHistory->count();
        $firstPurchase = $purchaseHistory->last()->created_at->format('d M Y');
        $lastPurchase = $purchaseHistory->first()->created_at->format('d M Y');
        
        $customerStats = [
            'totalSpent' => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
            'purchaseCount' => $purchaseCount,
            'firstPurchase' => $firstPurchase,
            'lastPurchase' => $lastPurchase
        ];
        
        return view('apoteker.customers.show', compact('customer', 'purchaseHistory', 'customerStats'));
    }
}
