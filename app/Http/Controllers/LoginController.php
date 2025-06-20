<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;
use Validator;
use Auth;
use Hash;

class LoginController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'documento' => 'required|numeric|unique:usuarios,documento',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->documento = $request->documento;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return redirect('login');
    }


    public function check(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('aseguradores');
        }

        return redirect('login')->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('login');
    }


}
