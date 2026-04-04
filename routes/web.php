<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
Route::add('GET', '/go', [Controller\Site::class, 'index']);
//Route::add('GET', '/employees', [Controller\Site::class, 'employees']);
//Route::add('GET', '/departments', [Controller\Site::class, 'departments']);
Route::add('GET', '/compositions', [Controller\Site::class, 'compositions']);



Route::add('GET', '/employees', [Controller\Site::class, 'employees'], ['hr_staff','admin']);
Route::add('GET', '/departments', [Controller\Site::class, 'departments'], ['hr_staff','admin']);
Route::add('GET', '/employee/create', [Controller\Site::class, 'employeeCreate'], ['hr_staff','admin']);
Route::add('POST', '/employee/create', [Controller\Site::class, 'employeeCreate'], ['hr_staff','admin']);

