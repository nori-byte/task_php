<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
Route::add('GET', '/go', [Controller\Site::class, 'index']);
Route::add(['GET', 'POST'], '/create', [Controller\Site::class, 'employeeCreate']);


Route::add(['GET', 'POST'], '/', [Controller\Site::class, 'hello']);

Route::add('GET', '/users', [Controller\Site::class, 'users']);
Route::add('GET', '/user/create', [Controller\Site::class, 'userCreate']);


// Сотрудники (для hr_staff и admin)
Route::add('GET', '/employees', [Controller\Site::class, 'employees']);
Route::add('GET', '/employee/edit', [Controller\Site::class, 'employeeEdit']);
Route::add('POST', '/employee/edit', [Controller\Site::class, 'employeeEdit']);
Route::add('GET', '/employee/delete', [Controller\Site::class, 'employeeDelete']);

// Подразделения
Route::add(['GET', 'POST'], '/departments', [Controller\Site::class, 'departments']);
Route::add('GET', '/department/user_create', [Controller\Site::class, 'departmentCreate']);
Route::add('POST', '/department/create', [Controller\Site::class, 'departmentCreate']);
Route::add('GET', '/department/edit', [Controller\Site::class, 'departmentEdit']);
Route::add('POST', '/department/edit', [Controller\Site::class, 'departmentEdit']);
Route::add('GET', '/department/delete', [Controller\Site::class, 'departmentDelete']);

// Отчёты и выбор по составу
Route::add('GET', '/compositions', [Controller\Site::class, 'compositions']);

