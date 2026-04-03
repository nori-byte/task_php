<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
Route::add('GET', '/go', [Controller\Site::class, 'index']);
Route::add('GET', '/categories', [Controller\Site::class, 'categories']);
Route::add('GET', '/departments', [Controller\Site::class, 'departments']);
Route::add('GET', '/employees', [Controller\Site::class, 'employees']);
Route::add('GET', '/positions', [Controller\Site::class, 'positions']);
