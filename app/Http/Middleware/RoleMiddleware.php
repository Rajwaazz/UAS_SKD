<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $guard = $role === 'admin' ? 'admin' : 'web';

        // Jika belum login
        if (!Auth::guard($guard)->check()) {
            return $role === 'admin'
                ? redirect()->route('admin.login')
                : redirect()->route('login');
        }

        // Jika sudah login, cek rolenya
        $user = Auth::guard($guard)->user();
        if ($user->role !== $role) {
            Auth::guard($guard)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $role === 'admin'
                ? redirect()->route('admin.login')
                    ->withErrors(['email' => 'You need admin privileges to access this area.'])
                : redirect()->route('login')
                    ->withErrors(['email' => 'You need user privileges to access this area.']);
        }

        return $next($request);
    }
}
