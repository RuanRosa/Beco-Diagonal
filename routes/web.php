<?php

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UserController@getAll');
            $router->get('/{id}', 'UserController@show');
            $router->post('/', 'UserController@create');
            $router->put('/{id}', 'UserController@update');
            $router->delete('/{id}', 'UserController@delete');
        });

        $router->group(['prefix' => 'transactions'], function () use ($router) {
            $router->post('/transfer', 'TransactionController@transfer');
        });

        $router->group(['prefix' => 'bank'], function () use ($router) {
            $router->get('/', 'BankController@getAll');
            $router->post('/deposit', 'BankController@deposit');
        });
    });
});
