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
        $accionesDisponibles = Accion::orderBy('modulo')->orderBy('nombre')->get();
        return view('usuarios.create', compact('accionesDisponibles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'         => 'required|string|max:255',
            'documento'      => 'required|string|max:20|unique:usuarios,documento',
            'email'          => 'required|string|email|max:255|unique:usuarios,email',
            'password'       => 'required|string|min:8|confirmed',
            'rol'            => 'required|string|max:50', // Considerar usar Rule::in(['admin', 'profesional', 'admision']) si los roles son fijos
            'acciones_ids'   => 'nullable|array',
            'acciones_ids.*' => ['integer', Rule::exists('acciones', 'id')],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $usuario = User::create([
            'nombre'    => $request->nombre,
            'documento' => $request->documento,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol'       => $request->rol,
            'estado'    => 1, // Por defecto activo
        ]);

        $accionesSeleccionadas = $request->input('acciones_ids', []);
        if (!empty($accionesSeleccionadas)) {
            // Asegurarse de que los IDs sean enteros, ya que el JSON los puede tratar como string a veces.
            $accionesSeleccionadas = array_map('intval', $accionesSeleccionadas);
            $usuario->permiso()->create(['acciones' => $accionesSeleccionadas]);
        }

        return redirect('usuarios')->with('success', 'Usuario creado correctamente con sus permisos.');
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
        $usuario = User::findOrFail($id);
        $accionesDisponibles = Accion::orderBy('modulo')->orderBy('nombre')->get();
        // Usar el accesor y la relación correcta
        $accionesAsignadasIds = $usuario->permiso ? $usuario->permiso->acciones : [];

        return view('usuarios.edit', compact('usuario', 'accionesDisponibles', 'accionesAsignadasIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Inyección de modelo User para $user
    {
        // Ya no es necesario User::find($id) gracias a la inyección de modelos de ruta.
        // $user ya está disponible.

        $validator = Validator::make($request->all(), [
            'nombre'         => 'required|string|max:255',
            'documento'      => ['required', 'string', 'max:20', Rule::unique('usuarios', 'documento')->ignore($user->id)],
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($user->id)],
            'password'       => 'nullable|string|min:8|confirmed',
            'rol'            => 'required|string|max:50', // Considerar Rule::in([...])
            'acciones_ids'   => 'nullable|array',
            'acciones_ids.*' => ['integer', Rule::exists('acciones', 'id')],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->nombre = $request->nombre;
        $user->documento = $request->documento;
        $user->email = $request->email;
        $user->rol = $request->rol;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Guardar los cambios del usuario

        // Lógica para actualizar permisos
        $accionesSeleccionadas = $request->input('acciones_ids', []);
        // Asegurarse de que los IDs sean enteros
        $accionesSeleccionadas = array_map('intval', $accionesSeleccionadas);

        if ($user->permiso) { // Si el usuario ya tiene un registro de permisos
            if (!empty($accionesSeleccionadas)) {
                $user->permiso->update(['acciones' => $accionesSeleccionadas]);
            } else {
                // Opción: Eliminar el registro de permiso si no se seleccionan acciones
                $user->permiso->delete();
                // Opción alternativa: Guardar un array vacío si se prefiere mantener el registro
                // $user->permiso->update(['acciones' => []]);
            }
        } elseif (!empty($accionesSeleccionadas)) { // Si no tiene registro previo pero se seleccionaron acciones
            $user->permiso()->create(['acciones' => $accionesSeleccionadas]);
        }

        return redirect()->route('usuarios.index') // Redirigir al index o a edit, según preferencia
                         ->with('message', 'Usuario actualizado correctamente con sus permisos.')
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

    // El método updatePermissions ya no es necesario, su lógica se integró en update()
    // public function updatePermissions(Request $request, User $user)
    // {
    //     // ...
    // }
}
