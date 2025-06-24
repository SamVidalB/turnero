<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Asegurador;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Paciente::with(['asegurador', 'categoria'])->where('estado', 1)->get();
        return view('pacientes.index', compact('data'));
    }

    public function trash()
    {
        $data = Paciente::with(['asegurador', 'categoria'])->where('estado', 0)->get();
        return view('pacientes.trash', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aseguradores = Asegurador::where('estado', 1)->orderBy('nombre')->get();
        $categorias = Categoria::where('estado', 1)->orderBy('nombre')->get();
        return view('pacientes.create', compact('aseguradores', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'           => 'required|string|max:255',
            'documento'        => 'required|string|max:20|unique:pacientes,documento',
            'fecha_nacimiento' => 'required|date',
            'asegurador_id'    => 'required|exists:aseguradores,id',
            'categoria_id'     => 'required|exists:categorias,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Paciente::create([
            'nombre'           => $request->nombre,
            'documento'        => $request->documento,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'asegurador_id'    => $request->asegurador_id,
            'categoria_id'     => $request->categoria_id,
            'estado'           => 1, // Por defecto activo
        ]);

        return redirect('pacientes')->with('success', 'Paciente creado correctamente.');
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
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return redirect('pacientes')->with('error', 'Paciente no encontrado.');
        }
        $aseguradores = Asegurador::where('estado', 1)->orderBy('nombre')->get();
        $categorias = Categoria::where('estado', 1)->orderBy('nombre')->get();
        return view('pacientes.edit', compact('paciente', 'aseguradores', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return redirect('pacientes')->with('error', 'Paciente no encontrado.');
        }

        $validator = Validator::make($request->all(), [
            'nombre'           => 'required|string|max:255',
            'documento'        => 'required|string|max:20|unique:pacientes,documento,' . $id,
            'fecha_nacimiento' => 'required|date',
            'asegurador_id'    => 'required|exists:aseguradores,id',
            'categoria_id'     => 'required|exists:categorias,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $paciente->nombre = $request->nombre;
        $paciente->documento = $request->documento;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->asegurador_id = $request->asegurador_id;
        $paciente->categoria_id = $request->categoria_id;
        $paciente->save();

        return redirect('pacientes')
            ->with('message', 'Paciente actualizado correctamente.')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return back()->with('error', 'Paciente no encontrado.');
        }

        $paciente->estado = ($paciente->estado) ? 0 : 1;
        $paciente->save();

        if ($paciente->estado) {
            return back()->with('message', 'Paciente restaurado correctamente.')->with('type', 'success');
        } else {
            return back()->with('message', 'Paciente eliminado correctamente.')->with('type', 'danger');
        }
    }
}
