<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserApiController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $user = new User();
        $user->nickname = $request->nickname;
        $user->name = $request->name;
        $user->email = $request->email;
        //Te hace el password cifrado.
        $user->password = Hash::make($request->password);
        $user->register_date = Carbon::now();

        //guardar el usuario IMPORTANTE!!
        $user->save();

        //Autenticación del usuario
        Auth::attempt([
            'email'=>$user->email,
            'password'=>$user->password
        ]);

        //Convierte el token en un texto plano que se puede devolver
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'messasge'=>'Usuario registrado correctamente',
            'data'=>$user,
            'token'=>$token,
            //Bearer es el tipo de token default
            'token_type'=>'Bearer'
        ]);
    }
}
