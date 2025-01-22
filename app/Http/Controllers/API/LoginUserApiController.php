<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserApiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserApiController extends Controller
{
    public function login(LoginUserApiRequest $request)
    {
        Auth::attempt([
           'email'=>$request->email,
           'password'=>$request->password
        ]);

        //Esto es para cargar el user.
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
               'message'=>'Usuario no logueado',
               'data'=>null,
            ]);
        }else{
            $token = $usuario->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message'=>'Usuario logueado correctamente',
                'data'=>$usuario,
                'token'=>$token,
                'type_token'=>'Bearer'
            ]);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::user()->tokens()->delete()){
            return response()->json([
                'message'=>'Logout correcto',
                'data'=>null,
            ]);
        }else {
            return response()->json([
                'message'=>'Imposible cerrar sesión',
                'data'=>null,
            ]);
        }
    }
}
