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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('logout', 'AuthenticateController@logout' );
Route::group(['prefix'=>'api'], function(){
    
    
    Route::resource('authenticate', 'AuthenticateController', ['only'=>['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
    //Grupo de rutas para obtener informacion de los terrenos
    Route::group(['prefix'=>'terreno'],function(){  
        Route::get('/{limit}/{offset}', 'TerrenoController@index');
        Route::get('/{id}', 'TerrenoController@show');
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

    Route::group(['prefix'=>'lado'],function(){
        Route::get('/{limit}/{offset}', 'LadoController@index');
        Route::get('/{id}', 'LadoController@show');
    });

    Route::group(['prefix'=>'manzana'],function(){  
            Route::get('/{id}', 'ManzanaController@show');
    });

    Route::group(['prefix'=>'suscriptor'], function(){
        Route::get('/{id}', 'ClienteController@show');
    });

    //Grupo de rutas encargado de gestionar las peticiones 
    //relacionadas con identificacion de propiedades de objetos espaciales
    Route::group(['prefix'=>'identify'],function(){
        Route::get('/{x}/{y}','IdentifyController@all');
        Route::get('/{x}/{y}/terreno','IdentifyController@identifyTerreno');
        Route::get('/{x}/{y}/manzana','IdentifyController@identifyManzana');

    });

});

/*Route::group(['prefix'=>'usuario'], function(){
   Route::get('/{id}', function($id){
       $user = User::find($id);
       $user->password = Hash::make($user->usuario);
       $user->save();
       
        return response()->json($user);
   });
});*/