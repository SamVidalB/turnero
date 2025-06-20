<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Asegurador;

use Validator;

class AseguradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Asegurador::where('estado', 1)->get();
        return view('aseguradores.index', compact('data'));
    }   


    public function trash()
    {
        $data = Asegurador::where('estado', 0)->get();
        return view('aseguradores.trash', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aseguradores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nit'    => 'required|digits_between:6,20|numeric|unique:aseguradores',
            'nombre' => 'required|max:255',
        ]);
 
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Asegurador::create([
            'nit'    => $request->nit,
            'nombre' => $request->nombre,
        ]);

        return redirect('aseguradores')->with('success', 'Asegurador creado correctamente.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asegurador = Asegurador::find($id);
        return view('aseguradores.edit', compact('asegurador'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $validator = Validator::make($request->all(), [
            'nit'    => 'required|digits_between:6,20|numeric|unique:aseguradores,nit,'.$id,
            'nombre' => 'required|max:255',
        ]);
 
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $asegurador = Asegurador::find($id);
        $asegurador->nit = $request->nit;
        $asegurador->nombre = $request->nombre;
        $asegurador->save();

        return redirect('aseguradores')
                    ->with('message', 'Asegurador actualizado correctamente.')
                    ->with('type', 'success');
   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asegurador = Asegurador::find($id);

        $asegurador->estado = ($asegurador->estado) ? 0 : 1;
        $asegurador->save();

        // Asegurador::find($id)->update(['estado' => 0]);
        if($asegurador->estado){
            return back()->with('message', 'Asegurador restaurado correctamente.')->with('type', 'success');
        }else{
            return back()->with('message', 'Asegurador eliminado correctamente.')->with('type', 'danger');
        }
    
    }
}
