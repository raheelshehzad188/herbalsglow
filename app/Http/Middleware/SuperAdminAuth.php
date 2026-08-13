<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SuperAdminAuth
{
    public function handle($request, Closure $next)
    {
        $admin = Session::get('admin');
        if (!$admin) {
            return redirect('/superadmin/login');
        }

        $role = is_object($admin) ? ($admin->role ?? 'store_admin') : ($admin['role'] ?? 'store_admin');
        if ($role !== 'super_admin') {
            return redirect('/admin/dashboard')->with([
                'msg' => 'Super admin access required.',
                'msg_type' => 'error',
            ]);
        }

        return $next($request);
    }
}
