<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteStoreAndPutRequestValidation extends FormRequest
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
            'cpf_cnpj' => [
                'required',
                'max:14',
                'min:11',
                Rule::unique('clientes', 'cpf_cnpj')->ignore($this->get('id')),
            ],
            'nome_ou_razao_social' => [
                'required',
                'max:128',
                'min:2',
            ],
            'email' => [
                'email',
                'nullable',
                'max:256',
            ],
            'telefone' => [
                'nullable',
                'max:15',
                'min:14',
            ]
        ];
    }

    public function messages()
    {
        return [
            'cpf_cnpj.required' => 'O campo CPF/CNPJ é obrigatório.',
            'cpf_cnpj.max' => 'O campo CPF/CNPJ informado aceita no máximo 18 caracteres.',
            'cpf_cnpj.min' => 'O campo CPF/CNPJ informado aceita no mínimo 14 caracteres.',
            'cpf_cnpj.unique' => 'O CPF/CNPJ informado já encontra-se sendo utilizado na base de dados.',

            'nome_ou_razao_social.required' => 'O campo Nome/Razão Social é obrigatório.',
            'nome_ou_razao_social.max' => 'O campo Nome/Razão Social informado aceita no máximo 18 caracteres.',
            'nome_ou_razao_social.min' => 'O campo Nome/Razão Social informado aceita no mínimo 14 caracteres.',
        ];
    }
}
