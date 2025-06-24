<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Categoria::where('estado', 1)->get();
        return view('categorias.index', compact('data'));
    }

    public function trash()
    {
        $data = Categoria::where('estado', 0)->get();
        return view('categorias.trash', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Categoria::create([
            'nombre' => $request->nombre,
            'estado' => 1, // Por defecto activo
        ]);

        return redirect('categorias')->with('success', 'Categoría creada correctamente.');
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
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return redirect('categorias')->with('error', 'Categoría no encontrada.');
        }
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return redirect('categorias')->with('error', 'Categoría no encontrada.');
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $categoria->nombre = $request->nombre;
        $categoria->save();

        return redirect('categorias')
            ->with('message', 'Categoría actualizada correctamente.')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return back()->with('error', 'Categoría no encontrada.');
        }

        $categoria->estado = ($categoria->estado) ? 0 : 1;
        $categoria->save();

        if ($categoria->estado) {
            return back()->with('message', 'Categoría restaurada correctamente.')->with('type', 'success');
        } else {
            return back()->with('message', 'Categoría eliminada correctamente.')->with('type', 'danger');
        }
    }
}
