<?php

use Src\Route;

Route::add('GET', '/', [Controller\Api::class, 'index']);
Route::add('POST', '/echo', [Controller\Api::class, 'echo']);


Route::add('POST', '/api_auth', [Controller\Api::class, 'api_auth']);
Route::add('POST', '/employees/grouped-by-department', [Controller\Api::class, 'employeesGroupedByDepartment']);