<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai admin untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            // Redirect users to their appropriate areas
            if ($user->role === 'pelanggan') {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke dashboard admin.');
            } elseif ($user->role === 'apoteker') {
                return redirect()->route('apoteker.dashboard')->with('error', 'Anda tidak memiliki akses ke dashboard admin.');
            }
            
            return redirect()->route('home')->with('error', 'Akses ditolak. Area khusus admin.');
        }

        return $next($request);
    }
} 