<?php


namespace App\Entities;


use Illuminate\Support\Facades\DB;

class Venda
{
    public static function listaTodasAsVendas(){
        return \App\Models\Venda::withTrashed()
            ->with('cliente', 'itens.produto.fornecedor')
            ->get();
    }

    public static function listaTodasAsVendasPorClienteId($clienteId){
        if(empty($clienteId) || $clienteId === 0)
            throw new \InvalidArgumentException('O campo Id do cliente não pode ser nulo ou zero!');

        return \App\Models\Venda::withTrashed()
            ->with('cliente', 'itens.produto.fornecedor')
            ->where('cliente_id', '=', $clienteId)
            ->get();
    }

    public static function retornaVendaPorId($id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do venda não pode ser nulo ou zero!');

        return \App\Models\Venda::withTrashed()
            ->with('itens.produto')
            ->find($id);
    }

    public function cadastra($novaVenda){
        if(empty($novaVenda->cliente_id) || $novaVenda->cliente_id === 0)
            throw new \InvalidArgumentException('O campo Id do cliente não pode ser nulo ou zero!');

        $cliente = Cliente::retornaClientePorId($novaVenda->cliente_id);

        if(!self::clienteExisteNaBaseDeDados($cliente))
            throw new \Exception('Não é possível realizar essa venda pois o cliente selecionado não existe na base de dados.');

        if(!self::clienteEstaComCadastroAtivo($cliente))
            throw new \Exception('Não é possível realizar essa venda pois o cliente '.$cliente->nome_ou_razao_social.' foi excluído da base de dados.');

        //Inicia operação de venda
        DB::beginTransaction();

        try {

            $venda = \App\Models\Venda::create([
                'cliente_id' => $novaVenda->cliente_id,
            ]);

            $contagemDeItens = 0;
            //Cadastra os itens de venda
            foreach ($novaVenda->itens as $item){
                $item = (object)$item;

                $contagemDeItens++;

                $novoItemDeVenda = new ItemVenda();
                $novoItemDeVenda->cadastra($item, $contagemDeItens, $venda->id);
            }

            DB::commit();

            return self::retornaVendaPorId($venda->id);
        }catch (\Exception $ex){
            DB::rollBack();

            throw $ex;
        }


    }

    public static function deleta($id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do venda não pode ser nulo ou zero!');

        $venda = self::retornaVendaPorId($id);

        if(!$venda)
            return $venda;

        return $venda->delete();
    }

    //Regras de negócio para a venda
    private static function clienteExisteNaBaseDeDados($cliente = null){
        return $cliente != null;
    }

    private static function clienteEstaComCadastroAtivo($cliente){
        return $cliente->status != 'Excluído';
    }
}
