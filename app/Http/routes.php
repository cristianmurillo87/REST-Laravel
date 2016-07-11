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
});
