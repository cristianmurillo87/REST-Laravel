<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;

class PredioController extends Controller
{
    public function find($id){
        $predio = DB::table('predios')
                  ->leftJoin('terrenos','predios.cod_predio','=','terrenos.cod_predio')
                  ->select('predios.gid', 'predios.cod_predio' , 'predios.direccion' , 
                           'predios.cod_act' , 'predios.cod_pred_n' ,  'predios.num_predia')
                  ->where('predios.cod_predio', $id)
                  ->orWhere('predios.cod_pred_n',$id)
                  ->orWhere('predios.num_predia',$id)               
                  ->orderBy('predios.gid')->get();
        if(!$predio){
            return [];
        }
        
        return $predio;
    }
}
