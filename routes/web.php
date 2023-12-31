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
$router->group(['middleware' => App\Http\Middleware\CorsMiddleware::class], function ($router) {

    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    // Public endpoints
    $router->post('user/login', 'User\UserAuthController@login');
    $router->post('user/auth', 'User\UserAuthController@authenticate');

    // These endpoints require user authentication
    $router->group(['middleware' => App\Http\Middleware\AuthenticateMiddleware::class], function ($router) {
        $router->get('users/{userID}/leads', 'Lead\LeadsController@getAllByUserID');
        $router->get('users/{userID}/leads/{leadID}', 'Lead\LeadsController@getSingle');
        $router->post('leads', 'Lead\LeadsController@create');
        $router->patch('leads', 'Lead\LeadsController@update');
        $router->delete('leads', 'Lead\LeadsController@deleteSingle');

        $router->get('users/{userID}/sales-appointments', 'SalesAppointment\SalesAppointmentsController@getAllByUserID');
        $router->get('users/{userID}/sales-appointments/{salesAppointmentID}', 'SalesAppointment\SalesAppointmentsController@getSingle');
        $router->post('sales-appointments', 'SalesAppointment\SalesAppointmentsController@create');
        $router->patch('sales-appointments', 'SalesAppointment\SalesAppointmentsController@update');
        $router->delete('sales-appointments', 'SalesAppointment\SalesAppointmentsController@deleteSingle');
    });
});
