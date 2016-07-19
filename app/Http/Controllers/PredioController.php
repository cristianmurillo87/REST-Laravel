<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Predio as Predio;

class PredioController extends Controller
{
    
    public function index($limit, $offset){
        $predio = DB::table('predios')->orderBy('gid')->limit($limit)->offset($offset)->get();
        $total = DB::table('predios')->count();
        if(!$predio){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Error al consultar los terrenos.')),404);
        }
        return response()->json(array('success'=>'true', 'total' => $total ,'data'=>$predio),200);
    }
    
    public function find($id){
        $predio = DB::table('predios')
                  ->leftJoin('terrenos','predios.cod_predio','=','terrenos.cod_predio')
                  ->select('predios.gid', 'predios.cod_predio' , 'predios.direccion', 
                           'predios.cod_act' , 'predios.cod_pred_n' ,  'predios.num_predia', DB::raw('st_astext(terrenos.the_geom) as wkt'))
                  ->where('predios.cod_predio', $id)
                  ->orWhere('predios.cod_pred_n',$id)
                  ->orWhere('predios.num_predia',$id)               
                  ->orderBy('predios.gid')->get();
        if(!$predio){
            return [];
        }
        
        return $predio;
    }
    
    
    public function show($id){
        $predio = $this->find($id);
        
        if(count($predio)< 1){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'No se encontró el predio solicitado.')),404);
        }
        
        return response()->json(array('success'=>'true', 'data' => $predio),200);
    }
}
