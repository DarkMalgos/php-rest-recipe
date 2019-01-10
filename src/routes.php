<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->post('/recipes', '\App\Controllers\RecipeController:post');
$app->get('/recipes/{id}', '\App\Controllers\RecipeController:get');
$app->get('/recipes', '\App\Controllers\RecipeController:all');
$app->put('/recipes/{id}', '\App\Controllers\RecipeController:update');
$app->delete('/recipes/{id}', '\App\Controllers\RecipeController:delete');
