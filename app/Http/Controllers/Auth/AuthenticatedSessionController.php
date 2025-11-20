<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'clave_usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // Intentar autenticación
        if (! \Auth::attempt($request->only('clave_usuario', 'password'), $request->filled('remember'))) {
            return back()->withErrors(['clave_usuario' => 'Credenciales inválidas.'])->withInput();
        }

        // Usuario autenticado
        $user = \Auth::user();

        // Si el usuario está desactivado (status == 0), impedir login
        if (isset($user->status) && !(bool) $user->status) {
            \Auth::logout();
            return back()->withErrors(['clave_usuario' => 'Usuario inactivo. Contacta a un administrador.'])->withInput();
        }

        // Login válido: regenerar sesión y redirigir
        $request->session()->regenerate();

        // Evitar dependencia a RouteServiceProvider inexistente; redirigir al dashboard
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
