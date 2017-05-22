<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \z1haze\Acl\Traits\HasAcl;

class User extends Authenticatable
{
    use Notifiable, HasAcl;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'level_id', 'cpf', 'rg', 'rg_emissor', 'rg_uf','data_nascimento','sexo',
        'naturalidade', 'estado_civil', 'cep', 'logradouro', 'numero', 'bairro', 'localidade', 'uf', 'skype',
        'telefone_fixo', 'telefone_celular', 'cod_subconta', 'ativo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token'
    ];
}
