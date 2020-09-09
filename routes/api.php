<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/*

Route::group(array('prefix' => 'api'), function (){
    Route::get('/', function (){
        return response()->json(['message' => 'Api conectada', 'status' => 'Conectado']);
    });

    Route::resource('clientes', 'ClienteController');
});
*/

Route::apiResource('clientes', 'Api\ClienteController');
Route::apiResource('fornecedores', 'Api\FornecedorController');
Route::apiResource('produtos', 'Api\ProdutoController');
Route::apiResource('vendas', 'Api\VendaController');
