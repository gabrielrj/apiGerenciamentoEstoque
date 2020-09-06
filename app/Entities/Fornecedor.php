<?php


namespace App\Entities;


use http\Exception\InvalidArgumentException;

class Fornecedor
{
    public static function listaTodosOsFornecedores(){
        return \App\Models\Fornecedor::withTrashed()
            ->with('produtos')
            ->get();
    }

    public static function retornaFornecedorPorId($id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do fornecedor não pode ser nulo ou zero!');

        return \App\Models\Fornecedor::withTrashed()
            ->with('produtos')
            ->find($id);
    }

    public function cadastra($novoFornecedor){
        if(!is_numeric($novoFornecedor->cpf_cnpj))
            throw new InvalidArgumentException('Erro ao tentar cadastrar o fornecedor! O CPF ou CNPJ só pode ser numérico.');

        $fornecedorAdicionado = \App\Models\Fornecedor::create([
            'cpf_cnpj' => $novoFornecedor->cpf_cnpj,
            'nome_ou_razao_social' => $novoFornecedor->nome_ou_razao_social,
            'data_nascimento' => $novoFornecedor->data_nascimento,
            'email' => $novoFornecedor->email,
            'telefone' => $novoFornecedor->telefone,
        ]);

        return self::retornaFornecedorPorId($fornecedorAdicionado->id);
    }

    public function atualiza($fornecedorParaAtualizar, $id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do fornecedor não pode ser nulo ou zero!');

        $fornecedor = $this->retornaFornecedorPorId($id);

        if(!$fornecedor)
            return $fornecedor;

        if(!is_numeric($fornecedorParaAtualizar->cpf_cnpj))
            throw new InvalidArgumentException('Erro ao tentar atualizar o fornecedor! O CPF ou CNPJ só pode ser numérico.');

        return $fornecedor->update([
            'cpf_cnpj' => $fornecedorParaAtualizar->cpf_cnpj,
            'nome_ou_razao_social' => $fornecedorParaAtualizar->nome_ou_razao_social,
            'data_nascimento' => $fornecedorParaAtualizar->data_nascimento,
            'email' => $fornecedorParaAtualizar->email,
            'telefone' => $fornecedorParaAtualizar->telefone,
        ]);
    }

    public static function deleta($id){
        if(empty($id) || $id === 0)
            throw new \InvalidArgumentException('O campo Id do fornecedor não pode ser nulo ou zero!');

        $fornecedor = self::retornaFornecedorPorId($id);

        if(!$fornecedor)
            return $fornecedor;

        return $fornecedor->delete();
    }
}
