<?php

namespace App\Http\Controllers\Api;

use App\Entities\Fornecedor;
use App\Http\Controllers\Controller;
use App\Http\Requests\FornecedorStoreAndPutRequestValidation;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    /**
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $fornecedores = Fornecedor::listaTodosOsFornecedores();

            return response()->json($fornecedores);
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
    public function store(FornecedorStoreAndPutRequestValidation $request)
    {
        try {
            $fornecedor = new Fornecedor();

            $novoFornecedor = $fornecedor->cadastra((object)$request->all());

            return response()->json($novoFornecedor, 201);
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
            $fornecedor = Fornecedor::retornaFornecedorPorId($id);

            if(!$fornecedor)
                return response()->json(['status' => 'error', 'message' => 'Fornecedor não encontrado!'], 404);

            return response()->json($fornecedor);
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
    public function update(FornecedorStoreAndPutRequestValidation $request, $id)
    {
        try {
            $fornecedor = new Fornecedor();

            $fornecedorAtualizado = $fornecedor->atualiza((object)$request->all(), $id);

            if(!$fornecedorAtualizado)
                return response()->json(['status' => 'error', 'message' => 'Não foi possível alterar os dados do cliente pois o id informado não retornou resultado!'], 404);

            return response()->json($fornecedorAtualizado);
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
            $fornecedor = Fornecedor::deleta($id);

            if(!$fornecedor)
                return response()->json(['status' => 'error', 'message' => 'Fornecedor não encontrado!'], 404);

            return response()->json(['status' => 'success', 'message' => 'Fornecedor excluído com sucesso.'], 200);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
}
