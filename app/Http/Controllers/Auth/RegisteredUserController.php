<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Obtener todos los usuarios excepto los administradores (user_type = 1)[cite: 16]
        $users = User::where('user_type', '!=', 1)->orderBy('name')->get();
        return view('auth.register', compact('users'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Se removió el try-catch global para permitir que los errores de validación se muestren correctamente en la vista
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'clave_usuario' => ['required', 'integer', 'unique:users,clave_usuario'],
            'user_type' => ['nullable', 'in:1,2,3'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'clave_usuario' => $request->clave_usuario,
            'user_type' => $request->user_type ?? 3, // por defecto Trabajador si no se envía[cite: 16]
            'status' => $request->has('status') ? (bool)$request->status : true,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('register')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Alterna el campo status de un usuario (activar / desactivar).[cite: 16]
     */
    public function toggle(User $user)
    {
        if ($user->user_type === 1) {
            return redirect()->route('register')->with('error', 'No se puede modificar el administrador');
        }

        $user->status = ! (bool) $user->status;
        $user->save();

        return redirect()->route('register')->with('success', 'Estado del usuario actualizado');
    }

    /**
     * Restablece la contraseña del usuario a una contraseña proporcionada o temporal.[cite: 16]
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        if ($user->user_type === 1) {
            return redirect()->route('register')->with('error', 'No se puede modificar el administrador');
        }

        $request->validate([
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if ($request->filled('new_password')) {
            $newPassword = $request->input('new_password');
        } else {
            try {
                $newPassword = substr(bin2hex(random_bytes(4)), 0, 8);
            } catch (\Exception $e) {
                $newPassword = 'Temp1234';
            }
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->route('register')->with('success', 'Contraseña restablecida. Nueva contraseña: ' . $newPassword);
    }
}