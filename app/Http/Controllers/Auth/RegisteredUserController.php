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
        // Obtener todos los usuarios excepto los administradores (user_type = 1)
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
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                // validar unique correctamente sobre la tabla users y la columna clave_usuario
                'clave_usuario' => ['required', 'integer', 'max_digits:10', 'unique:users,clave_usuario'],
                // user_type opcional pero si viene validar que sea 1,2 o 3
                'user_type' => ['nullable', 'in:1,2,3'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'clave_usuario' => $request->clave_usuario,
                'user_type' => $request->user_type ?? 3, // por defecto Trabajador si no se envía
                'status' => $request->has('status') ? (bool)$request->status : true,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            return redirect()->route('register')->with('success', 'Usuario creado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('register')->with('error', 'No se pudo crear el usuario');
        }
    }

    /**
     * Alterna el campo status de un usuario (activar / desactivar).
     */
    public function toggle(User $user)
    {
        // opcional: impedir cambiar admin
        if ($user->user_type === 1) {
            return redirect()->route('register')->with('error', 'No se puede modificar el administrador');
        }

        $user->status = ! (bool) $user->status;
        $user->save();

        return redirect()->route('register')->with('success', 'Estado del usuario actualizado');
    }

    /**
     * Restablece la contraseña del usuario a una contraseña proporcionada o temporal.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        // evitar modificar administrador
        if ($user->user_type === 1) {
            return redirect()->route('register')->with('error', 'No se puede modificar el administrador');
        }

        // si se envió una contraseña, validarla
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
