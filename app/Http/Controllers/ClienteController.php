<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Cliente as Cliente;
use Estratificacion\Http\Controllers\JsonController as JsonController;

class ClienteController extends Controller
{
    public function find($id){
        $cliente = DB::table('emcali_clientes as c')
                   ->select(
                       DB::raw("st_asgeojson(c.the_geom)::json as geometry"),
                       DB::raw("row_to_json((select j from (select c.gid, c.nombre, c.direccion, c.cod_predio, c.cod_cliente) as j)) as properties")
                   )
                   ->where('c.cod_cliente', '=', $id)
                   ->orWhere('c.cod_predio','=', $id)->get();
                   
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
        
        $geojson = JSONController::stringToGeoJson($cliente);
        
        return response()->json(array('success'=>'true', 'data'=>$geojson),200);

    }
}
