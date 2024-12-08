<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Cliente;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use PDOException;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ClienteController extends Controller
{

    public function clientes()
    {
        try {
            $clientes = DB::table('cursos')
                ->join('clientes', 'cursos.id', '=', 'clientes.curso_id')
                ->select('clientes.*', 'cursos.nombre as curso')
                ->orderBy('clientes.id', 'desc')
                ->get();
            
            return response()->json([
                'data' => $clientes,
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
            $validacion = Validator::make($request->all(), [
                'nombre'  => 'required',
                'telefono' => 'required|integer',
                'correo'  => 'required',
                'curso_id' => 'required'
            ]);
            if ($validacion->fails()) {
                return response()->json([
                    'mensaje' => 'Error de validación',
                    'errors' => $validacion->errors(),
                    'success' => false
                ], 400);
            }

            $cliente = new Cliente();
            $cliente->nombre = $request->input('nombre');
            $cliente->telefono = $request->input('telefono');
            $cliente->correo = $request->input('correo');
            $cliente->curso_id = $request->input('curso_id');
            $cliente->save();


            return response()->json([
                'mensaje' => 'Cliente creado correctamente',
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


    public function delete($id)
    {
        try {
            $cliente = Cliente::find($id);
            if ($cliente === null) {
                return response()->json([
                    'mensaje' => 'Cliente no encontrado',
                    'success' => false
                ], 403);
            }
            $cliente->delete();

            return response()->json([
                'mensaje' => 'Cliente eliminado correctamente',
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
            $clientes = Cliente::find($id);
            if ($clientes === null) {
                return abort(403);
            }

            return response()->json([
                'mensaje' => 'Cliente encontrado',
                'data' => $clientes,
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
            $validacion = Validator::make($request->all(), [
                'nombre'  => 'required',
                'telefono' => 'required|integer',
                'correo'  => 'required',
                'curso_id' => 'required'
            ]);

            if ($validacion->fails()) {
                return response()->json([
                    'mensaje' => 'Error de validación',
                    'errors' => $validacion->errors(),
                    'success' => false
                ], 400);
            }


            $cliente = Cliente::find($id);
            $cliente->nombre = $request->input('nombre');
            $cliente->telefono = $request->input('telefono');
            $cliente->correo = $request->input('correo');
            $cliente->curso_id = $request->input('curso_id');
            $cliente->save();


            return response()->json([
                'mensaje' => 'Cliente actualizado correctamente',
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
