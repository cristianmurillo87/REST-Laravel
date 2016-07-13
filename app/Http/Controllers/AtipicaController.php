<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Atipica as Atipica;

class AtipicaController extends Controller
{
    public function index($limit, $offset){
        $atipica = DB::table('atipicas')->orderBy('gid')->limit($limit)->offset($offset)->get();
        $total = DB::table('atipicas')->count();
        if(!$atipica){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Error al realizar consulta.')),404);
        }
        return response()->json(array('success'=>'true', 'total' => $total ,'data'=>$atipica),200);        
    }
    
    public function find($id){
        $atipica = DB::table('atipicas')
                   ->where('cod_predio',$id)->get();
        if(!$atipica){
            return [];
        }
        return $atipica;
   }
    
    public function show($id){
        $atipica = $this->find($id);
        
        if(count($atipica)< 1){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'No se encontró el registro solicitado.')),404);
        }
        
        return response()->json(array('success'=>'true', 'data' => $atipica),200);
    }
    
    
}
