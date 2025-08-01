<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelanggan untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        
        if ($user->role !== 'pelanggan') {
            // Redirect admin/apoteker to their dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak dapat mengakses area pelanggan sebagai admin.');
            } elseif ($user->role === 'apoteker') {
                return redirect()->route('apoteker.dashboard')->with('error', 'Anda tidak dapat mengakses area pelanggan sebagai apoteker.');
            }
            
            return redirect()->route('home')->with('error', 'Akses ditolak. Area khusus pelanggan.');
        }

        return $next($request);
    }
} 