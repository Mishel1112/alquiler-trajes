<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios y el formulario para registrar nuevo usuario.
     */
    public function index()
    {
        $usuarios = Usuario::with('rol')->orderBy('idUsuario', 'asc')->get();
        $roles = Rol::orderBy('idRol', 'asc')->get();

        return view('auth.usuario', compact('usuarios', 'roles'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'required|email|max:191|unique:usuario,email',
            'password' => 'required|string|min:6|same:password_confirmation',
            'password_confirmation' => 'required',
            'id_Rol' => 'required|exists:rol,idRol',
        ], [
            'nombre.required' => 'El campo Nombre(s) es obligatorio.',
            'apellido.required' => 'El campo Apellido(s) es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un formato de correo válido.',
            'email.unique' => 'Este correo ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.same' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'Debe confirmar la contraseña.',
            'id_Rol.required' => 'Debe seleccionar un rol.',
            'id_Rol.exists' => 'El rol seleccionado no es válido.',
        ]);

        Usuario::create([
            'nombre' => trim($request->nombre),
            'apellido' => trim($request->apellido),
            'telefono' => $request->filled('telefono') ? trim($request->telefono) : null,
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'idRol' => (int) $request->id_Rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * Actualiza la información de un usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $rules = [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'email' => 'required|email|max:191|unique:usuario,email,' . $usuario->idUsuario . ',idUsuario',
            'id_Rol' => 'required|exists:rol,idRol',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'nullable|string|min:6';
        }

        $request->validate($rules, [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está en uso por otro usuario.',
            'id_Rol.required' => 'Debe seleccionar un rol.',
        ]);

        $usuario->nombre = trim($request->nombre);
        $usuario->apellido = trim($request->apellido);
        $usuario->telefono = $request->filled('telefono') ? trim($request->telefono) : null;
        $usuario->email = trim($request->email);
        $usuario->idRol = (int) $request->id_Rol;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Evitar que el usuario actual se elimine a sí mismo
        if (Auth::check() && Auth::id() == $usuario->idUsuario) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propio usuario mientras tienes la sesión iniciada.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
