<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Http\Controllers\JsonController as JsonController;


class ManzanaController extends Controller
{
    
    public function identify($x, $y){
        if(is_numeric($x) && is_numeric($y)){
            $manzana = DB::select(DB::raw("select manzanas.gid as gid, manzanas.cod_manzan as cod_manzan,
                                barrios.nombre as barrio, st_astext(manzanas.the_geom) as geometry from manzanas 
                                left join barrios on manzanas.cod_barrio = barrios.cod_barrio 
                                where st_intersects(manzanas.the_geom, st_geomfromtext('POINT($x $y)',97393))"));
           
            return $manzana;            
        }
        
        return [];
    }
    
    public function find($id){
            $manzana = DB::table('manzanas as m')
            ->join('barrios as b','m.cod_barrio','=','b.cod_barrio')
            ->select(
                DB::raw("st_asgeojson(m.the_geom)::json as geometry"),
                DB::raw("row_to_json((select j from(select m.gid, m.cod_manzan, b.cod_barrio, b.nombre) as j)) as properties")
            )
            ->where("m.cod_manzan","=",$id)->get();

            if(!$manzana){
                return [];
            }
            
            return $manzana;
     }
        
        
     public function show($id){
           $manzana = $this->find($id);
            
           if(count($manzana)< 1){
               return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'No se encontró la manzana solicitada.')),404);
           }

            
           $geojson = JSONController::stringToGeoJson($manzana);
            
           return response()->json(array('success'=>'true', 'data' => $geojson),200);
     } 
}
