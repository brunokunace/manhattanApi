<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RateTypes extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'initial', 'emolumento', 'corretagem', 'ganho'
    ];

    public function results(){
        return $this->hasMany(Results::class);
    }
}
