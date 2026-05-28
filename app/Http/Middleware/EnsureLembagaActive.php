<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLembagaActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdminLembaga()) {
                if (!$user->lembaga || !$user->lembaga->is_active) {
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')->with('error', 'Lembaga Anda dinonaktifkan atau tidak terdaftar.');
                }
            }
        }

        return $next($request);
    }
}
