<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login','LoginController@login');
$router->post('/getReduct','ReductController@getReduct');
$router->post('/getAllReduct','ReductController@getAllReduct');
/*$router->post('/getmarques','ShopController@getmarques');
$router->post('/login','ShopController@login');*/

$router->post('/test','ReductController@test');
$router->get('/test','ReductController@test');

