<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;

class Atipica extends Model
{
    protected $table = 'atipicas';
    protected $timestamps = false;
    
    public function terreno(){
        return $this->belongsTo('Estratificacion\Terreno','cod_predio','cod_predio');
    }
    
    public function lado(){
        return $this->belongsTo('Estratificacion\Lado','lado_manz','lado_manz');
    }
}
