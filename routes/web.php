<?php

/** @var \Laravel\Lumen\Routing\Router $router */


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


$router->group(['middleware' => App\Http\Middleware\CorsMiddleware::class], function ($router) {
    $router->post('user/login', 'User\UserAuthController@login');
    $router->post('user/auth', 'User\UserAuthController@authenticate');

    $router->get('users/{userID}/leads', 'Lead\LeadsController@getAllByUserID');
    // Add more routes here...
});
