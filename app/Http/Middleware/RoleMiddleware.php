<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = Auth::user();

        if (!$user || $user->role !== $role) {
            if ($user) {
                $redirectRoute = match ($user->role) {
                    'admin' => 'admin.dashboard',
                    'dokter' => 'dokter.dashboard',
                    'pasien' => 'pasien.dashboard',
                    default => 'login',
                };

                return redirect()->route($redirectRoute)
                    ->with('error', "Akses Ditolak: Akun Anda (" . ucfirst($user->role) . ") tidak memiliki hak akses untuk membuka halaman {$role}.");
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
