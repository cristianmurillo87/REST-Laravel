<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Http\Controllers\JsonController as JsonController;


class BarrioController extends Controller
{
    public function find($id){
        $barrio = DB::table('barrios as b')
                  ->select(
                       DB::raw("st_asgeojson(b.the_geom)::json as geometry"),
                       DB::raw("row_to_json((select j from (select b.gid, b.cod_comuna, b.cod_barrio, b.nombre) as j)) as properties")
                   )
                  ->where('b.cod_barrio', '=', strtoupper($id))
                  ->orWhere('b.nombre', '=', strtoupper($id))->get();
                   
        if(!$barrio){
            return [];
        }
        
        return $barrio;
    }
    
    public function show($id){
        $barrio = $this->find($id);
        
        if(count($barrio) < 1){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Barrio no encontrado.')),404);
        }
        
        $geojson = JSONController::stringToGeoJson($barrio);
        
        return response()->json(array('success'=>'true', 'data'=>$geojson),200);

    }    
}
