<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

    protected $fillable = ['nome', 'categoria', 'valor_produto', 'quantidade_itens', 'fornecedor_id'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

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

    public function fornecedor(){
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }
}
