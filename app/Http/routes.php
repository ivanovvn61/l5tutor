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

Route::resource('/', 'IndexController', ['only' => ['index'], 'names' => ['index' => 'home']]);
Route::resource('portfolios', 'PortfolioController', ['parameters' => ['portfolios' => 'alias']]);
Route::get('articles/cat/{cat_alias?}', ['uses' => 'ArticlesController@index', 'as' => 'articlesCat'])->where('cat_alias', '[\w-]+');
Route::resource('articles', 'ArticlesController', ['parameters' => ['articles' => 'alias']]);
Route::resource('comment', 'CommentController', ['only' => ['store']]);
Route::match(['get', 'post'], '/contacts', ['uses' => 'ContactsController@index', 'as' => 'contacts']);
Route::get('forbidden', ['uses' => 'ForbiddenController@show', 'as' => 'forbidden']);

//php artisan make:auth
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

//admin
Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function () {
    //admin
    Route::get('/', ['uses' => 'IndexController@index', 'as' => 'adminIndex']);
    // articles
    Route::resource('/articles', 'ArticlesController');
    Route::resource('/permissions', 'PermissionsController');
    Route::resource('/users', 'UsersController');
    //menus
    Route::resource('/menus', 'MenusController');
});
