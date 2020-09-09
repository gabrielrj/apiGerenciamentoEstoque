<?php

namespace App\Http\Controllers\Api;

use App\Entities\Venda;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $vendas = Venda::listaTodasAsVendas();

            return response()->json($vendas);
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
            $dadosVenda = (object)$request->all();

            $venda = new Venda();

            $novaVenda = $venda->cadastra($dadosVenda);

            return response()->json($novaVenda, 201);
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
            $venda = Venda::retornaVendaPorId($id);

            if(!$venda)
                return response()->json(['status' => 'error', 'message' => 'Venda não encontrada!'], 404);

            return response()->json($venda);
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
            $venda = Venda::deleta($id);

            if(!$venda)
                return response()->json(['status' => 'error', 'message' => 'Venda não encontrada!'], 404);

            return response()->json(['status' => 'success', 'message' => 'Venda excluída com sucesso.'], 200);
        }catch (\Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
}
