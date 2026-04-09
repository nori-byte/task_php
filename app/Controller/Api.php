<?php

namespace Controller;

use Model\Employee;
use Src\Request;
use Src\View;

class Api
{
    public function index(): void
    {
        $employees = Employee::all()->toArray();

        (new View())->toJSON($employees);
    }

    public function echo(Request $request): void
    {
        (new View())->toJSON($request->all());
    }
}