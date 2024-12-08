<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use PDOException;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function users()
    {
        try {

            $users = DB::table('users')
                ->get();

            return response()->json([
                'data' => $users,
                'success' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'errors' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function post(Request $request)
    {
        try {

            //Validaciones
            $request->validate([
                'nombre' => 'required',
                'correo' => 'required',
                'password' => 'required',
            ]);

            $user = new User();
            $user->nombre = $request->input('nombre');
            $user->correo = $request->input('correo');
            $user->password = Hash::make($request->input('password'));

            if ($user->save()) {
                return response()->json([
                    'mensaje' => 'Usuario creado correctamente',
                    'success' => true
                ], 200);
            }

        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'errors' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function delete($id)
    {
        try {

            $user = User::find($id);

            if ($user === null) {
                return response()->json([
                    'mensaje' => 'Usuario no encontrado',
                    'success' => false
                ], 404);
            }

            $user->delete();

            return response()->json([
                'mensaje' => 'Usuario eliminado',
                'success' => true
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'errors' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::find($id);

            if ($user === null) {
                return response()->json([
                    'mensaje' => 'Usuario no encontrado',
                    'success' => false
                ], 404);
            }

            return response()->json([
                'data' => $user,
                'success' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'errors' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function put(Request $request, $id)
    {
        try {

            $user = User::find($id);

            $validacion = Validator::make($request->all(), [
                'nombre' => 'required',
                'correo' => 'required'
            ]);

            if ($validacion->fails()) {
                return response()->json([
                    'mensaje' => 'Datos invalidos',
                    'errors' => $validacion->errors(),
                    'success' => false
                ], 400);
            }


            $user->nombre = $request->input('nombre');
            $user->correo = $request->input('correo');

            $user->save();

            return response()->json([
                'mensaje' => 'Usuario actualizado',
                'success' => true
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.',
                'errors' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
