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
// Inserir user
Route::post('/addUser','UsersController@addUser');


// login user
Route::post('/login','UsersController@login');

// Rota para configurar user
Route::post('/updateAvatar', 'UsersController@updateAvatar');

// Rota para configurar user
Route::get('/getImage/{image}', 'UsersController@getImage');

// Inserir piada
Route::put('/updateNome','UsersController@updateNome');

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

    // Busca set likes
    Route::post('/like','PiadasController@postLikePiada');

    // Busca todas as piadas
    Route::get('/categorias','PiadasController@getCategorias');

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