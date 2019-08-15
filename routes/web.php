<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/ 

//rota chama a view Index
Route::get('/', 'PiadaController@viewIndex'); 

//rota chama a view Create Piada
Route::get('/create_piada', 'PiadaController@ViewCreatePiada');

//rota chama a view Index
Route::get('/home', 'PiadaController@viewIndex'); 

//rota chama a view Index
Route::get('/reset', 'UserController@viewReset'); 

//rota chama a view editar 
Route::get('/editar/{id}', 'PiadaController@viewEditar');

//rota chama a funcao para excluir  piada
Route::get('/excluir/{id}', 'PiadaController@delete');

/*
|--------------------------------------------------------------------------
| Routes UserController
|--------------------------------------------------------------------------
*/

// Rota para configurar user
Route::get('/settings', 'UserController@settings');

// Rota para configurar user
Route::post('/settings', 'UserController@updateAvatar');


/*
|--------------------------------------------------------------------------
| Functions post Routes
|--------------------------------------------------------------------------
*/


// Rota para criar piada
Route::post('/create_piada', 'PiadaController@createPiada');

// Rota para criar piada
Route::post('/logout', 'PiadaController@viewLogin');


//rota chama a funcao update 
Route::post('/editar/{id}', 'PiadaController@update');

 
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
