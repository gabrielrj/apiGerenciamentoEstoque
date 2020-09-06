<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use Uuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = ['cpf_cnpj', 'nome_ou_razao_social', 'data_nascimento', 'email', 'telefone'];

    protected $keyType = 'string';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at',
        'deleted_at',
    ];

    protected $appends = ['status', 'data_cadastro', 'data_exclusao'];

    public function getStatusAttribute(){
        return $this->attributes['deleted_at'] == null ? 'Ativo' : 'Excluído';
    }

    public function getDataCadastroAttribute(){
        return $this->attributes['created_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format("d/m/Y H:i");
    }

    public function getDataExclusaoAttribute(){
        return $this->attributes['deleted_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['deleted_at'])->format("d/m/Y H:i");
    }

    public function vendas(){
        return $this->hasMany(Venda::class, 'cliente_id');
    }
}
