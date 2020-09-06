<?php


namespace App\Entities;


use http\Exception\InvalidArgumentException;

class Cliente
{
    public static function listaTodosOsClientes(){
        return \App\Models\Cliente::withTrashed()
            ->with('vendas.itens.produto.fornecedor')
            ->get();
    }

    public static function retornaClientePorId($id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do cliente não pode ser nulo ou zero!');

        return \App\Models\Cliente::withTrashed()
            ->with('vendas.itens.produto.fornecedor')
            ->find($id);
    }

    public function cadastra($novoCliente){

        if(!is_numeric($novoCliente->cpf_cnpj))
            throw new InvalidArgumentException('Erro ao tentar cadastrar o cliente! O CPF ou CNPJ só pode ser numérico.');

        $clienteAdicionado = \App\Models\Cliente::create([
            'cpf_cnpj' => $novoCliente->cpf_cnpj,
            'nome_ou_razao_social' => $novoCliente->nome_ou_razao_social,
            'data_nascimento' => $novoCliente->data_nascimento,
            'email' => $novoCliente->email,
            'telefone' => $novoCliente->telefone,
        ]);

        return self::retornaClientePorId($clienteAdicionado->id);
    }

    public function atualiza($clienteParaAtualizar, $id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do cliente não pode ser nulo ou zero!');

        $cliente = $this->retornaClientePorId($id);

        if(!$cliente)
            return $cliente;

        if(!is_numeric($clienteParaAtualizar->cpf_cnpj))
            throw new InvalidArgumentException('Erro ao tentar alterar o cliente! O CPF ou CNPJ só pode ser numérico.');

        return $cliente->update([
            'cpf_cnpj' => $clienteParaAtualizar->cpf_cnpj,
            'nome_ou_razao_social' => $clienteParaAtualizar->nome_ou_razao_social,
            'data_nascimento' => $clienteParaAtualizar->data_nascimento,
            'email' => $clienteParaAtualizar->email,
            'telefone' => $clienteParaAtualizar->telefone,
        ]);
    }

    public static function deleta($id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do cliente não pode ser nulo ou zero!');

        $cliente = self::retornaClientePorId($id);

        if(!$cliente)
            return $cliente;

        return $cliente->delete();
    }
}
