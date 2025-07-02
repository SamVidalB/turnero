<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\PuntoAtencion; // Importar PuntoAtencion
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Importar DB para transacciones

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Sede::where('estado', 1)->get();
        return view('sedes.index', compact('data'));
    }

    public function trash()
    {
        $data = Sede::where('estado', 0)->get();
        return view('sedes.trash', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sedes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:255|unique:sedes,nombre',
            'direccion' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:100',
            'cantidad_admision' => 'nullable|integer|min:0',
            'cantidad_consulta' => 'nullable|integer|min:0',
            'cantidad_postconsulta' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Usar una transacción también para el store por consistencia y seguridad de datos
        $sede = DB::transaction(function () use ($request) {
            $nuevaSede = Sede::create([
                'nombre'    => $request->nombre,
                'direccion' => $request->direccion,
                'municipio' => $request->municipio,
                'estado'    => 1, // Por defecto activo
            ]);

            // Lógica para crear Puntos de Atención
            $tiposPuntos = [
                'Admision' => 'Admisión',
                'Consulta' => 'Consulta',
                'Postconsulta' => 'Postconsulta'
            ];

            foreach ($tiposPuntos as $keyRequest => $nombreBase) {
                $cantidad = $request->input('cantidad_' . strtolower($keyRequest), 0);
                if ($cantidad > 0) {
                    for ($i = 1; $i <= $cantidad; $i++) {
                        PuntoAtencion::create([
                            'sede_id' => $nuevaSede->id,
                            'nombre' => $nombreBase . '-' . $i,
                        ]);
                    }
                }
            }
            return $nuevaSede; // Devolver la sede creada para uso externo si fuera necesario
        });

        // La variable $sede aquí contendrá el resultado de la transacción (la nueva sede)
        // o null si la transacción falló y no se lanzó una excepción explícita antes.
        // En una transacción exitosa, $sede es la $nuevaSede.

        if ($sede) {
            return redirect('sedes')->with('success', 'Sede y puntos de atención creados correctamente.');
        } else {
            // Esto no debería ocurrir si la transacción se maneja correctamente
            // y lanza excepciones en caso de error, o si create falla y devuelve null.
            return redirect('sedes/create')->with('error', 'Hubo un problema al crear la sede.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // No es común usarlo en CRUDs simples
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sede = Sede::with('puntosAtencion')->find($id); // Cargar puntos de atención
        if (!$sede) {
            return redirect('sedes')->with('error', 'Sede no encontrada.');
        }
        // Para el formulario, podríamos pre-rellenar las cantidades si quisiéramos,
        // pero el plan actual es que el usuario especifique las nuevas cantidades.
        // Por ahora, solo pasamos la sede con sus puntos (podría ser útil para mostrar los actuales).
        return view('sedes.edit', compact('sede'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sede = Sede::find($id);
        if (!$sede) {
            return redirect('sedes')->with('error', 'Sede no encontrada.');
        }

        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:255|unique:sedes,nombre,' . $id,
            'direccion' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:100',
            // Añadir validación para las cantidades si se desea (ej. numérico, min:0)
            'cantidad_admision' => 'nullable|integer|min:0',
            'cantidad_consulta' => 'nullable|integer|min:0',
            'cantidad_postconsulta' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request, $sede) {
            $sede->nombre = $request->nombre;
            $sede->direccion = $request->direccion;
            $sede->municipio = $request->municipio;
            $sede->save();

            // Eliminar puntos de atención existentes
            $sede->puntosAtencion()->delete();

            // Lógica para (re)crear Puntos de Atención
            $tiposPuntos = [
                'Admision' => 'Admisión',
                'Consulta' => 'Consulta',
                'Postconsulta' => 'Postconsulta'
            ];

            foreach ($tiposPuntos as $keyRequest => $nombreBase) {
                $cantidad = $request->input('cantidad_' . strtolower($keyRequest), 0);
                if ($cantidad > 0) {
                    for ($i = 1; $i <= $cantidad; $i++) {
                        PuntoAtencion::create([
                            'sede_id' => $sede->id,
                            'nombre' => $nombreBase . '-' . $i,
                        ]);
                    }
                }
            }
        });

        return redirect('sedes')
            ->with('message', 'Sede y puntos de atención actualizados correctamente.')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sede = Sede::find($id);
        if (!$sede) {
            return back()->with('error', 'Sede no encontrada.');
        }

        $sede->estado = ($sede->estado) ? 0 : 1;
        $sede->save();

        if ($sede->estado) {
            return back()->with('message', 'Sede restaurada correctamente.')->with('type', 'success');
        } else {
            return back()->with('message', 'Sede eliminada correctamente.')->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource in JSON format.
     */
    public function showJson(string $id)
    {
        $sede = Sede::with('puntosAtencion')->find($id);

        if (!$sede) {
            return response()->json(['message' => 'Sede no encontrada'], 404);
        }

        return response()->json($sede);
    }
}
