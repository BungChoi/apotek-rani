<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApoteker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai apoteker untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        
        if ($user->role !== 'apoteker') {
            // Redirect pelanggan to home, admin to admin dashboard
            if ($user->role === 'pelanggan') {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke dashboard apoteker.');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Gunakan dashboard admin untuk akses penuh.');
            }
            
            return redirect()->route('home')->with('error', 'Akses ditolak. Area khusus apoteker.');
        }

        return $next($request);
    }
} 