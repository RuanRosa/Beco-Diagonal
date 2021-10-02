<?php

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/', function () {
        return "teste";
    });
});
