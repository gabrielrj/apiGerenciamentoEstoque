<?php

namespace App\Http\Controllers;

use App\Entities\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $produtos = Produto::listaTodosOsProdutos();

            return response()->json($produtos);
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
    public function store(Request $request)
    {
        try {
            $dadosProduto = (object)$request->all();

            $produto = new Produto();

            $novoProduto = $produto->cadastra($dadosProduto);

            return response()->json($novoProduto, 201);
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
            $produto = Produto::retornaProdutoPorId($id);

            if(!$produto)
                return response()->json(['status' => 'error', 'message' => 'Produto não encontrado!'], 404);

            return response()->json($produto);
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
    public function update(Request $request, $id)
    {
        try {
            $dadosProduto = (object)$request->all();

            $produto = new Produto();

            $produtoAtualizado = $produto->atualiza($dadosProduto, $id);

            if(!$produtoAtualizado)
                return response()->json(['status' => 'error', 'message' => 'Não foi possível alterar os dados do produto pois o id informado não retornou resultado!'], 404);

            return response()->json($produtoAtualizado);
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
            $produto = Produto::deleta($id);

            if(!$produto)
                return response()->json(['status' => 'error', 'message' => 'Produto não encontrado!'], 404);

            return response()->json(['status' => 'success', 'message' => 'Produto excluído com sucesso.'], 200);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
}
