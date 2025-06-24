<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Sede::create([
            'nombre'    => $request->nombre,
            'direccion' => $request->direccion,
            'municipio' => $request->municipio,
            'estado'    => 1, // Por defecto activo
        ]);

        return redirect('sedes')->with('success', 'Sede creada correctamente.');
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
        $sede = Sede::find($id);
        if (!$sede) {
            return redirect('sedes')->with('error', 'Sede no encontrada.');
        }
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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sede->nombre = $request->nombre;
        $sede->direccion = $request->direccion;
        $sede->municipio = $request->municipio;
        $sede->save();

        return redirect('sedes')
            ->with('message', 'Sede actualizada correctamente.')
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
}
