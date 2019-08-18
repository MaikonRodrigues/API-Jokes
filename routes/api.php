<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 
Route::get('/status', function(){
    return['status' => 'ok']; 
});

Route::namespace('Api')->group(function(){

  /*
|--------------------------------------------------------------------------
| API Routes Users
|--------------------------------------------------------------------------
|
    */
// Inserir piada
Route::post('/addUser','UsersController@addUser');


    /*
|--------------------------------------------------------------------------
| API Routes Piadas
|--------------------------------------------------------------------------
|
    */
    // Busca imagem do user
    Route::get('/image/{name}','PiadasController@getImage');
    // Busca todas as piadas
    Route::get('/piadas','PiadasController@piadas');

    // Busca piada pelo id
    Route::get('/piadas/{id}','PiadasController@getPiadas');

    // Busca user pelo id
    Route::get('/user/{id}','PiadasController@getUser');

    // Inserir piada
    Route::post('/piadas','PiadasController@addPiada');

    // Inserir piada
    Route::put('/piadas/{id}','PiadasController@atualizarPiada');

     // Inserir piada
     Route::delete('/deletePiada/{id}','PiadasController@deletarPiada');
});