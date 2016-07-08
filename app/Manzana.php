<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;

class Manzana extends Model
{
    protected $table = 'manzanas';
    protected $timestamps = false;
    
    public function terrenos(){
        return $this->hasMany('Estratificacion\Terreno');
    }
    
    public function lados(){
        return $this->hasMany('Estratificacion\Lado');    
    }
    
    public function barrio(){
        return $this->belongsTo('Estratificacion\Barrio', 'cod_barrio','cod_barrio');
    }
    
}
