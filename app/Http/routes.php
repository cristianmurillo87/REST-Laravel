<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'terreno'],function(){
    
    /*Route::get('/',function(){
        return "Listado de terrenos";
    });*/
    
    Route::get('/{id?}', function($id = null){
        if($id!= null){
            return "Propiedades del terreno $id.";
        }
        else{
            return "Listado de terrenos.";
        }
    });
    
    Route::post('/create', function(){
        return "Terreno creado correctamente.";
    });
    
    Route::put('edit/{id}', function($id){
        return "Terreno $id editado correctamente.";
    });
    
    Route::delete('delete/{id}',function($id){
        return "Terreno $id eliminado correctamente.";
    });
    
    
});
