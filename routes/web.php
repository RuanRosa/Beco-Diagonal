<?php

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UserController@getAll');
            $router->post('/', 'UserController@create');
            $router->DELETE('/{id}', 'UserController@delete');
            $router->get('/{id}', 'UserController@show');
        });
    });
});
