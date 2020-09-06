<?php


namespace App\Entities;


class Produto
{

    public static function listaTodosOsProdutos(){
        return \App\Models\Produto::withTrashed()
            ->with('fornecedor')
            ->get();
    }

    public static function listaTodosOsProdutosPorFornecedorId($fornecedorId){
        if(empty($fornecedorId) || $fornecedorId === 0)
            throw new \InvalidArgumentException('O campo Id do fornecedor não pode ser nulo ou zero!');

        return \App\Models\Produto::withTrashed()
            ->with('fornecedor')
            ->where('fornecedor_id', '=', $fornecedorId)
            ->get();
    }

    public static function retornaProdutoPorId($id){
        if($id == null || $id == 0)
            throw new \InvalidArgumentException('O campo Id do produto não pode ser nulo ou zero!');

        return \App\Models\Produto::withTrashed()
            ->with('fornecedor')
            ->find($id);
    }

    public function cadastra($novoProduto){
        if(empty($novoProduto->fornecedor_id) || $novoProduto->fornecedor_id === 0)
            throw new \InvalidArgumentException('O campo Id do fornecedor não pode ser nulo ou zero!');

        $produtoAdicionado = \App\Models\Produto::create([
            'nome' => $novoProduto->nome,
            'categoria' => $novoProduto->categoria,
            'valor_produto' => $novoProduto->valor_produto,
            'quantidade_itens' => $novoProduto->quantidade_itens,
            'fornecedor_id' => $novoProduto->fornecedor_id,
        ]);

        return self::retornaProdutoPorId($produtoAdicionado->id);
    }

    public function atualiza($produtoParaAtualizar, $id){
        if($id == null || $id == 0)
            throw new \InvalidArgumentException('O campo Id do produto não pode ser nulo ou zero!');

        $produto = $this->retornaProdutoPorId($id);

        if(!$produto)
            throw new \Exception('Não foi possível atualizar os dados do produto pois não foi encontrado nenhum produto com o id informado!');

        return $produto->update([
            'nome' => $produtoParaAtualizar->nome,
            'categoria' => $produtoParaAtualizar->categoria,
            'valor_produto' => $produtoParaAtualizar->valor_produto,
            'quantidade_itens' => $produtoParaAtualizar->quantidade_itens,
            'fornecedor_id' => $produtoParaAtualizar->fornecedor_id,
        ]);
    }

    public static function deleta($id){
        if($id == null || $id == 0)
            throw new \InvalidArgumentException('O campo Id do produto não pode ser nulo ou zero!');

        $produto = self::retornaFornecedorPorId($id);

        if(!$produto)
            throw new \Exception('Não foi possível deletar o produto pois não foi encontrado nenhum produto com o id informado!');

        return $produto->delete();
    }

    public static function realizaBaixaNoEstoque($id, $quantidadeParaBaixa){
        if($quantidadeParaBaixa == null || $quantidadeParaBaixa <= 0)
            throw new \InvalidArgumentException('Não é possível realizar a baixa no estoque devido a quantidade informada ser Nula, Zero ou Abaixo de zero.');

        $produto = \App\Models\Produto::find($id);

        if(!self::produtoExisteNoBancoDeDados($produto))
            throw new \Exception('Não é possível concluir a venda pois um dos produtos adicionados não existe no catálogo!');
        if(!self::produtoEstaAtivoNoCatalogo($produto))
            throw new \Exception('Não é possível concluir a venda pois o produto '.$produto->nome.' foi excluído do catálogo!');
        if(!self::produtoComQuantidadeAcimaDeZeroEmEstoque($produto))
            throw new \Exception('Não é possível concluir a venda pois o produto '.$produto->nome.' encontra-se com estoque zerado!');
        if(!self::quantidadeDeItensRestantesDoProdutoIgualOuAcimaDeZero($produto, $quantidadeParaBaixa))
            throw new \Exception('Não é possível concluir a venda pois a quantidade de itens do produto '.$produto->nome.' encontra-se menor que a quantidade solicitada para venda!');

        $produto->quantidade_itens = $produto->quantidade_itens - $quantidadeParaBaixa;
        return $produto->save();
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

    private static function quantidadeDeItensRestantesDoProdutoIgualOuAcimaDeZero($produto, $quantidadeParaBaixa){
        return ($produto->quantidade_itens - $quantidadeParaBaixa) >= 0;
    }
}
