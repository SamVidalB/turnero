<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Accion;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importar DB facade
use Illuminate\Support\Facades\Log; // Importar Log facade

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('nombre')->get();
        $acciones = Accion::orderBy('nombre')->get();
        return view('permisos.index', compact('users', 'acciones'));
    }

    /**
     * Get the actions for a specific user.
     * Devuelve un JSON con los IDs de las acciones permitidas para el usuario.
     */
    public function getUserAcciones(User $user)
    {
        $permiso = $user->permisos()->first(); // Asume que User tiene una relación 'permisos' (hasOne o hasMany)
        $accionesUsuarioIds = [];

        if ($permiso && !empty($permiso->acciones)) {
            // La columna 'acciones' en la tabla 'permisos' almacena un JSON array de IDs de acciones.
            $decodedAcciones = json_decode($permiso->acciones, true);
            if (is_array($decodedAcciones)) {
                $accionesUsuarioIds = $decodedAcciones;
            } else {
                // Log por si el contenido no es un JSON válido o está vacío de forma inesperada
                Log::warning("Permisos no es un JSON válido para el usuario {$user->id}: " . $permiso->acciones);
            }
        }
        return response()->json(['acciones_usuario' => $accionesUsuarioIds]);
    }

    /**
     * Update the permissions for a specific user.
     */
    public function update(Request $request, User $user)
    {
        // Laravel ya convierte los checkboxes con [] en un array.
        // Si no se selecciona ninguno, no estará presente en el request, por eso el default a [].
        $accionesPermitidasIds = array_map('intval', $request->input('acciones_permitidas', []));

        // Validar que los IDs de acción recibidos realmente existen en la tabla 'acciones'
        // Esto es una medida de seguridad, aunque la UI solo debería enviar IDs válidos.
        $accionesExistentesIds = Accion::whereIn('id', $accionesPermitidasIds)->pluck('id')->toArray();

        // Solo trabajar con los IDs que realmente existen para evitar errores.
        $accionesValidasParaGuardar = array_intersect($accionesPermitidasIds, $accionesExistentesIds);

        DB::beginTransaction();
        try {
            // Usamos updateOrCreate para manejar tanto la creación (si no existen permisos para el usuario)
            // como la actualización (si ya existen).
            // La columna 'acciones' guardará un array JSON de los IDs de las acciones permitidas.
            Permiso::updateOrCreate(
                ['usuario_id' => $user->id],
                ['acciones' => json_encode($accionesValidasParaGuardar)]
            );

            DB::commit();
            return redirect()->route('permisos.index')->with('success', 'Permisos actualizados correctamente para ' . $user->name);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar permisos para usuario {$user->id}: " . $e->getMessage());
            return redirect()->route('permisos.index')->with('error', 'Error al actualizar los permisos. Intente de nuevo.');
        }
    }
}
