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
use Illuminate\Http\Response as Response;
use Estratificacion\Terreno as Terreno;

Route::get('/', function () {
    return view('welcome');
});

//Grupo de rutas para obtener informacion de los terrenos
Route::group(['prefix'=>'terreno'],function(){  
    Route::get('/{limit}/{offset}', 'TerrenoController@index');
    Route::get('/{id}', 'TerrenoController@show');
    Route::get('/{x}/{y}/identify','TerrenoController@identify');
});

//Grupo de rutas para obtener informacion de los predios
Route::group(['prefix'=>'predio'],function(){  
    Route::get('/{limit}/{offset}', 'PredioController@index');
    Route::get('/{id}', 'PredioController@show');
});

//Grupo de rutas para obtener informacion de los predios
Route::group(['prefix'=>'atipicidad'],function(){  
    Route::get('/{limit}/{offset}', 'AtipicaController@index');
    Route::get('/{id}', 'AtipicaController@show');
});

//Grupo de rutas encargado de gestionar las peticiones 
//relacionadas con identificacion de propiedades de objetos espaciales
Route::group(['prefix'=>'identify'],function(){
    Route::get('/{x}/{y}','IdentifyController@all');
    Route::get('/{x}/{y}/terreno','IdentifyController@identifyTerreno');
    Route::get('/{x}/{y}/manzana','IdentifyController@identifyManzana');

});