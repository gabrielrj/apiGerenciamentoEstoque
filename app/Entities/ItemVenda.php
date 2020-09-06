<?php

namespace App\Entities;

use Illuminate\Support\Facades\DB;

class ItemVenda
{
    public static function listaTodosOsItensDeVenda(){
        return \App\Models\ItemVenda::withTrashed()
            ->with('venda')
            ->get();
    }

    public static function listaTodosOsItensDeVendaPorVendaId($vendaId){
        if(empty($vendaId) || $vendaId === 0)
            throw new \InvalidArgumentException('O campo Id da venda não pode ser nulo ou zero!');

        return \App\Models\ItemVenda::withTrashed()
            ->with('venda')
            ->where('venda_id', '=', $vendaId)
            ->get();
    }

    public static function retornaItemDeVendaPorId($id){
        if($id == null || $id == 0)
            throw new \InvalidArgumentException('O campo Id do item de venda não pode ser nulo ou zero!');

        return \App\Models\ItemVenda::withTrashed()
            ->with('venda')
            ->find($id);
    }

    public function cadastra($item, $numeroItem, $vendaId){
        if(empty($vendaId) || $vendaId === 0)
            throw new \InvalidArgumentException('O campo Id da venda não pode ser nulo ou zero!');

        $produto = Produto::retornaProdutoPorId($item->produto_id);

        if(!self::produtoExisteNoBancoDeDados($produto))
            throw new \Exception('Não é possível concluir a venda pois um dos produtos adicionados não existe no catálogo!');
        if(!self::produtoEstaAtivoNoCatalogo($produto))
            throw new \Exception('Não é possível concluir a venda pois o produto '.$produto->nome.' foi excluído do catálogo!');
        if(!self::produtoComQuantidadeAcimaDeZeroEmEstoque($produto))
            throw new \Exception('Não é possível concluir a venda pois o produto '.$produto->nome.' encontra-se com estoque zerado!');
        if(!self::quantidadeDeItensDeVendaSolicitadaEMenorQueQuantidadeDoProdutoEmEstoque($produto, $item))
            throw new \Exception('Não é possível concluir a venda pois a quantidade de itens do produto '.$produto->nome.' encontra-se menor que a quantidade solicitada para venda!');
        if(!self::descontoPraticadoNoItemDeVendaMenorOuIgualQuePrecoDoProduto($produto, $item))
            throw new \Exception('Não é possível concluir a venda pois o desconto praticado na venda do produto '.$produto->nome.' é maior que o valor do produto!');

        try {
            Produto::realizaBaixaNoEstoque($produto->id, $item->quantidade_itens);

            $itemDeVendaAdicionado = \App\Models\ItemVenda::create([
                'valor_item' => $produto->valor_produto - ($item->desconto == null ? 0 : $item->desconto),
                'desconto' => $item->desconto == null ? 0 : $item->desconto,
                'numero_item' => $numeroItem,
                'quantidade_itens' => $item->quantidade_itens,
                'produto_id' => $produto->id,
                'venda_id' => $vendaId,
            ]);
        }catch (\Exception $ex){
            throw $ex;

        }

        return self::retornaItemDeVendaPorId($itemDeVendaAdicionado->id);
    }

    public static function deleta($id){
        if($id == null || $id == 0)
            throw new \InvalidArgumentException('O campo Id do item de venda não pode ser nulo ou zero!');

        $itemDeVenda = self::retornaItemDeVendaPorId($id);

        if(!$itemDeVenda)
            return $itemDeVenda;

        return $itemDeVenda->delete();
    }


    private static function produtoExisteNoBancoDeDados($produto){
        return $produto != null;
    }

    private static function produtoEstaAtivoNoCatalogo($produto){
        return $produto->status != 'Excluído';
    }

    private static function produtoComQuantidadeAcimaDeZeroEmEstoque($produto){
        return $produto->quantidade_itens > 0;
    }

    private static function quantidadeDeItensDeVendaSolicitadaEMenorQueQuantidadeDoProdutoEmEstoque($produto, $itemDeVenda){
        return $produto->quantidade_itens > $itemDeVenda->quantidade_itens;
    }

    private static function descontoPraticadoNoItemDeVendaMenorOuIgualQuePrecoDoProduto($produto, $itemDeVenda){
        return $itemDeVenda->desconto <= $produto->valor_produto;
    }
}
