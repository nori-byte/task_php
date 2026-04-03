<?php

namespace Controller;

use Model\Department;
use Model\Employee;
use Model\Post;
use Src\View;
use Src\Request;
use Model\User;
use Src\Auth\Auth;
use Src\Validator\Validator;


class Site
{


    public function departments(Request $request): string
    {
        $departments = Department::all();
        $employees = Employee::all(['id_department', 'birth_date']);

        // Считаем средний возраст для каждого подразделения
        $avgAge = [];
        foreach ($employees as $emp) {
            if (!$emp->birth_date) continue;
            $age = date_diff(date_create($emp->birth_date), date_create('today'))->y;
            $deptId = $emp->id_department;

            if (!isset($avgAge[$deptId])) {
                $avgAge[$deptId] = ['sum' => 0, 'cnt' => 0];
            }
            $avgAge[$deptId]['sum'] += $age;
            $avgAge[$deptId]['cnt']++;
        }

        // Добавляем avg_age к каждому подразделению
        foreach ($departments as $dept) {
            if (isset($avgAge[$dept->id_department])) {
                $dept->avg_age = round($avgAge[$dept->id_department]['sum'] / $avgAge[$dept->id_department]['cnt']);
            } else {
                $dept->avg_age = null;
            }
        }

        return (new View())->render('site.department', ['departments' => $departments]);
    }
    public function index(Request $request): string
    {
        $posts = Post::all();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function employees(Request $request): string
    {
        $employees  = Employee::all();
        return (new View())->render('site.employee', ['employees' => $employees]);
    }
//    public function departments(Request $request): string
//    {
//        $departments = Department::all();
//        return (new View())->render('site.department', ['departments' => $departments]);
//    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'working']);
    }

//    public function signup(Request $request): string
//    {
//        if ($request->method === 'POST' && User::create($request->all())) {
//            app()->route->redirect('/go');
//        }
//        return new View('site.signup');
//    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST') {

            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'login' => ['required', 'unique:users,login'],
                'password' => ['required']
            ], [
                'required' => 'поле :field пусто',
                'unique' => 'поле :field должно быть уникально'
            ]);

            if($validator->fails()){
                // Собираем все ошибки в одну строку
                $errorMessages = [];
                foreach ($validator->errors() as $field => $messages) {
                    $errorMessages[] = implode(', ', $messages);
                }
                $message = implode(' и ', $errorMessages);
                return new View('site.signup', ['message' => $message]);
            }

            if (User::create($request->all())) {
                app()->route->redirect('/login');
            }
        }
        return new View('site.signup');
    }
    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }
}

