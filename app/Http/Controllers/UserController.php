<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Accion; // Importar el modelo Accion
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Importar Rule para validaciones avanzadas

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::where('estado', 1)->get();
        return view('usuarios.index', compact('data'));
    }

    public function trash()
    {
        $data = User::where('estado', 0)->get();
        return view('usuarios.trash', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:255',
            'documento' => 'required|string|max:20|unique:usuarios,documento',
            'email'     => 'required|string|email|max:255|unique:usuarios,email',
            'password'  => 'required|string|min:8|confirmed',
            'rol'       => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'nombre'    => $request->nombre,
            'documento' => $request->documento,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol'       => $request->rol,
            'estado'    => 1, // Por defecto activo
        ]);

        return redirect('usuarios')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Generalmente no se usa para CRUDs simples, se puede dejar vacío o implementar si es necesario
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id); // Usar findOrFail para error 404 si no se encuentra
        $accionesDisponibles = Accion::orderBy('modulo')->orderBy('nombre')->get();
        $accionesAsignadasIds = $usuario->acciones->pluck('id')->toArray();

        return view('usuarios.edit', compact('usuario', 'accionesDisponibles', 'accionesAsignadasIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return redirect('usuarios')->with('error', 'Usuario no encontrado.');
        }

        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:255',
            'documento' => 'required|string|max:20|unique:usuarios,documento,' . $id,
            'email'     => 'required|string|email|max:255|unique:usuarios,email,' . $id,
            'password'  => 'nullable|string|min:8|confirmed', // Password es opcional en la actualización
            'rol'       => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $usuario->nombre = $request->nombre;
        $usuario->documento = $request->documento;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect('usuarios')
            ->with('message', 'Usuario actualizado correctamente.')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return back()->with('error', 'Usuario no encontrado.');
        }

        $usuario->estado = ($usuario->estado) ? 0 : 1;
        $usuario->save();

        if ($usuario->estado) {
            return back()->with('message', 'Usuario restaurado correctamente.')->with('type', 'success');
        } else {
            return back()->with('message', 'Usuario eliminado correctamente.')->with('type', 'danger');
        }
    }

    /**
     * Update the user's permissions.
     */
    public function updatePermissions(Request $request, User $user) // Inyección de modelo User
    {
        $request->validate([
            'acciones_ids'   => 'nullable|array',
            'acciones_ids.*' => [ // Validar cada elemento del array
                'integer',
                Rule::exists('acciones', 'id'), // Asegurar que cada ID exista en la tabla acciones
            ],
        ]);

        $user->acciones()->sync($request->input('acciones_ids', []));

        return redirect()->route('usuarios.edit', $user->id)
                         ->with('message', 'Permisos actualizados correctamente.')
                         ->with('type', 'success');
    }
}
