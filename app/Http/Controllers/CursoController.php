<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use PDOException;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CursoController extends Controller
{
    public function verCursos()
    {
        try {

            return response()->json([
                'mensaje' => 'Cursos encontrados',
                'data' => Curso::all(),
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

            $request->validate([
                'nombre' => 'required',
                'descripcion' => 'required'
            ]);

            $curso = new Curso();
            $curso->nombre = $request->input('nombre');
            $curso->descripcion = $request->input('descripcion');
            $curso->save();
            return response()->json([
                'mensaje' => 'Curso creado correctamente',
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
        $cursos = Curso::find($id);
        return response()->json([
            'mensaje' => 'Curso encontrado',
            'data' => $cursos,
            'success' => true
        ], 200);
    }

    public function put(Request $request, $id)
    {
        $validacion = $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        if (!$validacion) {
            return response()->json([
                'mensaje' => 'Error de validación',
                'success' => false
            ], 400);

        } else {
            $curso = Curso::find($id);
            $curso->nombre = $request->input('nombre');
            $curso->descripcion = $request->input('descripcion');
            $curso->save();
            return response()->json([
                'mensaje' => 'Curso actualizado correctamente',
                'success' => true
            ], 200);
        }
    }

    public function delete($id)
    {
        $curso = Curso::find($id);
        $curso->delete();

        return response()->json([
            'mensaje' => 'Curso eliminado correctamente',
            'success' => true
        ], 200);
    }

    /*CLIENTE CURSO */

    public function show2($id)
    {
        $clientes = DB::table('cursos')
            ->join('clientes', 'cursos.id', '=', 'clientes.curso_id')
            ->select('clientes.*', 'cursos.nombre as curso')
            ->where('cursos.id', '=', $id)
            ->get();

        return response()->json([
            'mensaje' => 'Clientes encontrados',
            'data' => $clientes,
            'success' => true
        ], 200);
    }

    public function deleteClienteCurso($id)
    {
        $cliente = Cliente::find($id);
        $curso = DB::table('cursos')
            ->join('clientes', 'cursos.id', '=', 'clientes.curso_id')
            ->select('cursos.id')
            ->where('clientes.id', '=', $id)
            ->get();
        $cliente->delete();

        foreach ($curso as $cursoo) {
            $id_curso = $cursoo->id;
        }


        return redirect()->route('ver', [$id_curso])->with(['msg' => "operacion realizada"]);
    }

    public function postClienteCurso(Request $request)
    {
        $request->validate([
            'nombre'  => 'required',
            'edad'    => 'required|integer',
            'telefono' => 'required|integer',
            'correo'  => 'required|email|unique:clientes',
            'curso_id' => 'required'
        ]);

        $cliente = new Cliente();
        $cliente->nombre = $request->input('nombre');
        $cliente->edad = $request->input('edad');
        $cliente->telefono = $request->input('telefono');
        $cliente->correo = $request->input('correo');
        $cliente->curso_id = $request->input('curso_id');
        $cliente->save();

        $id = $request->input('curso_id');


        return redirect()->route('ver', [$id])->with(['msg' => "operacion realizada"]);
    }
}
