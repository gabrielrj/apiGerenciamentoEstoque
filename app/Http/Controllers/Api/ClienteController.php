<?php

namespace App\Http\Controllers\Api;

use App\Entities\Cliente;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteStoreAndPutRequestValidation;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $clientes = Cliente::listaTodosOsClientes();

            return response()->json($clientes);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    public function listaClientesAtivos(){
        try {
            $clientesAtivos = Cliente::listaTodosOsClientesAtivos();

            return response()->json($clientesAtivos);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteStoreAndPutRequestValidation $request)
    {
        try {
            $cliente = new Cliente();

            $novoCliente = $cliente->cadastra((object)$request->all());

            return response()->json($novoCliente, 201);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $cliente = Cliente::retornaClientePorId($id);

            if(!$cliente)
                return response()->json(['status' => 'error', 'message' => 'Cliente não encontrado!'], 404);

            return response()->json($cliente);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteStoreAndPutRequestValidation $request, $id)
    {
        try {
            $cliente = new Cliente();

            $clienteAtualizado = $cliente->atualiza((object)$request->all(), $id);

            if(!$clienteAtualizado)
                return response()->json(['status' => 'error', 'message' => 'Não foi possível alterar os dados do cliente pois o id informado não retornou resultado!'], 404);

            return response()->json($clienteAtualizado);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $cliente = Cliente::deleta($id);

            if(!$cliente)
                return response()->json(['status' => 'error', 'message' => 'Cliente não encontrado!'], 404);

            return response()->json(['status' => 'success', 'message' => 'Cliente excluído com sucesso.'], 200);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
}
