<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemVenda extends Model
{
    use SoftDeletes;

    protected $fillable = ['valor_item', 'desconto', 'numero_item', 'quantidade_itens', 'produto_id', 'venda_id'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $appends = ['status', 'data_cadastro', 'data_exclusao', 'valor_item_formatado', 'desconto_formatado'];

    public function getStatusAttribute(){
        return $this->attributes['deleted_at'] == null ? 'Ativo' : 'Excluído';
    }

    public function getDataCadastroAttribute(){
        return $this->attributes['created_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format("d/m/Y H:i");
    }

    public function getDataExclusaoAttribute(){
        return $this->attributes['deleted_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['deleted_at'])->format("d/m/Y H:i");
    }

    public function getValorItemFormatadoAttribute(){
        $valorItem = $this->attributes['valor_item'];

        return 'R$ '.number_format($valorItem, 2, ',', '.');
    }

    public function getDescontoFormatadoAttribute(){
        $desconto = $this->attributes['desconto'];

        return 'R$ '.number_format($desconto, 2, ',', '.');
    }

    public function produto(){
        return $this->belongsTo(Produto::class, 'produto_id')->withTrashed();
    }

    public function venda(){
        return $this->belongsTo(Venda::class, 'venda_id');
    }
}
