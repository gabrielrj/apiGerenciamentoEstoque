<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdutoStoreAndPutRequestValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => [
                'required',
                'min:2',
                'max:128',
            ],
            'categoria' => [
                'required',
                'min:2',
                'max:128',
            ],
            'valor_produto' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'quantidade_itens' => [
                'required',
                'integer',
                'min:1',
            ],
            'fornecedor_id' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'valor_produto.required' => 'Campo valor do produto obrigatório.',
            'valor_produto.numeric' => 'Campo valor do produto deve ser numérico.',
            'valor_produto.min' => 'Campo valor do produto deve receber no mínimo 1 centavo.',

            'quantidade_itens.required' => 'Campo quantidade de itens é obrigatório.',
            'quantidade_itens.integer' => 'Campo quantidade de itens deve ser um número inteiro positivo.',
            'quantidade_itens.min' => 'Mínimo de 1 item para campo quantidade de ítens.',

            'fornecedor_id' => 'Campo fornecedor do produto é obrigatório.',
        ];
    }
}
