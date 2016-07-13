<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;

class LadoController extends Controller
{
    public function find($id, $byManzana = false){
        $lado = array();
        if(!$byManzana){
            $lado = DB::table('lados')
                ->where('lado_manz',$id)->orderBy('lado_manz')->get();
        }
        else{
            $lado = DB::table('lados')
                ->where('cod_manzana',$id)->orderBy('lado_manz')->get();
        }
                
        if(!$lado){
            return [];
        }
       return $lado;
    }
    
}
