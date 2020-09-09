<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    use Uuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'cliente_id'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['updated_at'];

    protected $appends = ['status', 'data_cadastro', 'data_exclusao', 'valor_total'];

    public function getStatusAttribute(){
        return $this->attributes['deleted_at'] == null ? 'Efetivada' : 'Excluída';
    }

    public function getDataCadastroAttribute(){
        return $this->attributes['created_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format("d/m/Y H:i");
    }

    public function getDataExclusaoAttribute(){
        return $this->attributes['deleted_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['deleted_at'])->format("d/m/Y H:i");
    }

    public function getValorTotalAttribute(){
        $valorTotal = $this->itens()->sum('valor_item');

        return 'R$ '.number_format($valorTotal, 2, ',', '.');
    }

    public function itens(){
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id')->withTrashed();
    }
}
