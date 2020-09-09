<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendaStoreAndPutRequestValidation extends FormRequest
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
            'cliente_id' => [
                'required'
            ],
            'itens' => [
                'min:1'
            ],
            'itens.*.produto_id' => [
                'required',
            ],
            'itens.*.valor_item' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'itens.*.desconto' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'itens.*.quantidade_itens' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'O campo de seleção de cliente é obrigatório.',
            'itens.min' => 'É obrigatório no mínimo de 1 item para efetivar a compra.',
            'itens.*.produto_id.required' => 'Em todos os itens é obrigatório a seleção do produto.',

            'itens.*.valor_item.required' => 'Em todos os itens é obrigatório o preenchimento do valor total.',
            'itens.*.valor_item.numeric' => 'Em todos os itens o valor total deve ser um valor numérico positivo.',
            'itens.*.valor_item.min' => 'Em todos os itens o valor total deve ser de no mínimo 1 centavo.',

            'itens.*.desconto.numeric' => 'Em todos os itens o desconto deve ser um valor numérico positivo ou zero.',
            'itens.*.desconto.min' => 'Não é permitido aplicar um desconto negativo.',

            'itens.*.quantidade_itens.required' => 'Em todos os itens a quantidade de itens é de preenchimento obrigatório.',
            'itens.*.quantidade_itens.integer' => 'Em todos os itens a quantidade de itens deve ser um número inteiro positivo ou zero.',
            'itens.*.quantidade_itens.min' => 'Em todos os itens a quantidade de itens deve ser maior que zero.',
        ];
    }
}
