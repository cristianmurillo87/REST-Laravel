<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Cliente as Cliente;

class ClienteController extends Controller
{
    public function find($id){
        $cliente = Cliente::select('gid','nombre','direccion','cod_predio','cod_cliente',DB::raw('st_astext(the_geom) as wkt'))
                   ->where('cod_cliente',$id)
                   ->orWhere('cod_predio',$id)->get();
        if(!$cliente){
            return [];
        }
        
        return $cliente;
    }
    
    public function show($id){
        $cliente = $this->find($id);
        
        if(count($cliente)<1){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Suscriptor no encontrado.')),404);
        }
        return response()->json(array('success'=>'true', 'data'=>$cliente),200);

    }
}
