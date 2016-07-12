<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Terreno;

class TerrenoController extends Controller
{
    public function identify($x, $y){
        if(is_numeric($x) && is_numeric($y)){
            $terreno = DB::select(DB::raw("select terrenos.gid as gid, terrenos.cod_predio as cod_predio, 
                                terrenos.cod_manzan as cod_manzan, tipo_actividad.nombre as actividad, 
                                terrenos.direccion as direccion, terrenos.lado_manz as lado_manz, 
                                st_astext(terrenos.the_geom) as geometry, lados.estrato from terrenos 
                                left join tipo_actividad on terrenos.cod_act = tipo_actividad.cod_act 
                                left join lados on terrenos.lado_manz = lados.lado_manz
                                where st_intersects(terrenos.the_geom, st_geomfromtext('POINT($x $y)',97393))"));
           
            return $terreno;            
        }
        
        return [];
    
    }
    
    public function find($id){
         $terreno = DB::table('terrenos')
                    ->leftJoin('lados','terrenos.lado_manz','=', 'lados.lado_manz')
                    ->select('terrenos.gid','terrenos.cod_predio','terrenos.cod_manzan','terrenos.lado_manz',
                             'terrenos.direccion','terrenos.cod_act','lados.estrato', 
                              DB::raw('st_astext(terrenos.the_geom) as wkt'))
                    ->where('cod_predio','=',$id)->get();
          if(!$terreno){
              return [];
          }
          
          return $terreno;
    }
    
    
    /*
    *Despliega la lista de terrenos en forma de informacion para paginacion.
    *@param $limit -> cantidad de registros que debe devolver la consulta.
    *@param $offset -> id (gid) a partir del cual se generan los $limit resultados.
    *@return Response.   
    */
    public function index($limit, $offset){
        $terreno = DB::table('terrenos')
                    ->leftJoin('tipo_actividad','terrenos.cod_act','=','tipo_actividad.cod_act')
                    ->select('terrenos.gid as gid','terrenos.cod_predio as cod_predio','terrenos.cod_manzan as cod_manzan',
                             'tipo_actividad.nombre as actividad', 'terrenos.direccion as direccion', 'terrenos.lado_manz as lado_manz')
                    ->orderBy('gid')->limit($limit)->offset($offset)->get();
                    
         $total = DB::table('terrenos')->count();

        if(!$terreno){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Error al consultar los terrenos.')),500);
        }
        
        return response()->json(array('success'=>'true','total'=> $total ,'data' => $terreno),200);
        
    }
    
    /*
    *Devuelve informacion geografica y alfanumerica relacionada con el terreno solicitado.
    *@param $id -> numero matriz del terreno
    *@return Response
    */
    public function show($id){

        $terreno = $this->find($id);
        
        if( count($terreno) < 1 ){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'No se encontró el terreno solicitado.')),404);
        }
        
        return response()->json(array('success'=>'true', 'data' => $terreno),200);
        
    }                  

}


        /*
        $consulta = "select a.gid gid, a.cod_predio cod_predio, a.cod_manzan cod_manzan, 
        b.nombre actividad, a.direccion direccion, a.lado_manz lado_manz 
	    from  terrenos a left outer join tipo_actividad b on a.cod_act=b.cod_act 
        order by a.cod_predio, a.gid limit ".$limite." offset ".$cantidad;
        
        $users = DB::table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();
        
        $users = DB::table('users')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->get();
            
        $users = DB::table('users')
                     ->select(DB::raw('count(*) as user_count, status'))
                     ->where('status', '<>', 1)
                     ->groupBy('status')
                     ->get();
                     
        $consulta = "select st_astext(the_geom) wkt from barrios where cod_barrio='".$buscar."'";
        
        */