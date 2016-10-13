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
//use Illuminate\Hashing;
use Illuminate\Http\Response as Response;
use Estratificacion\Terreno as Terreno;
use Estratificacion\User as User;



Route::group(['prefix' => 'api'], function() {
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
    Route::get('logout', 'AuthenticateController@logout' );
});


Route::group(['prefix'=>'api', 'middleware'=>'jwt.auth'], function(){
    //Grupo de rutas para obtener informacion de los terrenos
    Route::group(['prefix'=>'terrenos'],function(){  
        Route::get('/{limit}/{offset}', 'TerrenoController@index');
        Route::get('/{id}', 'TerrenoController@show');
    });

    //Grupo de rutas para obtener informacion de los predios
    Route::group(['prefix'=>'predios'],function(){  
        Route::get('/{limit}/{offset}', 'PredioController@index');
        Route::get('/{id}', 'PredioController@show');
    });

    //Grupo de rutas para obtener informacion de los predios
    Route::group(['prefix'=>'atipicas'],function(){  
        Route::get('/{limit}/{offset}', 'AtipicaController@index');
        Route::get('/{id}', 'AtipicaController@show');
    });

    Route::group(['prefix'=>'lados'],function(){
        Route::get('/{limit}/{offset}', 'LadoController@index');
        Route::get('/{id}', 'LadoController@show');
    });

    Route::group(['prefix'=>'manzanas'],function(){  
            Route::get('/{id}', 'ManzanaController@show');
    });

    Route::group(['prefix'=>'suscriptores'], function(){
        Route::get('/{id}', 'ClienteController@show');
    });

    Route::group(['prefix'=>'barrios'], function(){
        Route::get('/{id}', 'BarrioController@show');
    });
    
    Route::group(['prefix'=>'comunas'], function(){
        Route::get('/{id}', 'ComunaController@show');
    });
    
    //Grupo de rutas encargado de gestionar las peticiones 
    //relacionadas con identificacion de propiedades de objetos espaciales
    Route::group(['prefix'=>'identify'],function(){
        Route::get('/{terreno}/{manzana}','ExtIdentifyController@identify');
    });

});

