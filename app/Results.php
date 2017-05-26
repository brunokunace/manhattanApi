<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Results extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
     'corretora', 'conta', 'titular', 'sub_conta', 'sub_titular', 'clordid', 'ativo', 'lado', 'status', 'criacao',
     'ultima_atualizacao', 'preco', 'preco_stop', 'quantidade', 'preco_medio', 'quantidade_executada', 'quantidade_restante',
     'total', 'total_executado', 'validade', 'data_validade', 'estrategia', 'mensagem', 'emolumento', 'corretagem', 'ganho',
     'historical_result_id', 'rate_types_id'
    ];
    
}
