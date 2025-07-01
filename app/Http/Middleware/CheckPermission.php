<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Accion; // Asegúrate de importar el modelo Accion

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $routeName  La ruta nominal que se está verificando
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $routeName = $request->route()->getName();

        // Permitir siempre el acceso a rutas básicas que no deberían estar restringidas por acciones
        // o que son necesarias para el funcionamiento (ej. logout, dashboard si es una landing page)
        $allowedRoutes = ['logout', 'dashboard', 'login', 'register', 'login.check', 'register.store'];
        if (in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }

        // Si el usuario es un tipo de superadmin o tiene un rol especial, podríamos bypassar la verificación aquí
        // Por ejemplo: if ($user->rol === 'superadmin') return $next($request);

        if ($routeName && $user->hasAccionRuta($routeName)) {
            return $next($request);
        }

        // Si la ruta no tiene nombre o el usuario no tiene el permiso
        // abort(403, 'Acceso denegado. No tienes permiso para acceder a esta página.');
        // O redirigir a una vista específica:
        // return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta página.');
        // Por ahora, un simple abort 403.
        return response()->view('errors.403', [], 403);
    }
}
