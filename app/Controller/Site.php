<?php

namespace Controller;

use Model\Department;
use Model\Employee;
use Model\Post;
use Model\Composition;
use Src\View;
use Src\Request;
use Model\User;
use Src\Auth\Auth;
use Src\Validator\Validator;


class Site
{
    public function compositions(Request $request): string
    {
        $compositions = Composition::all();
        $selectedCompositions = $_GET['composition_ids'] ?? [];
        if (!is_array($selectedCompositions)) {
            $selectedCompositions = [];
        }
        $employees = [];
        if (!empty($selectedCompositions)) {
            $employees = Employee::with(['position', 'department', 'composition'])
                ->whereIn('id_composition', $selectedCompositions)
                ->get();
        }
        return (new View())->render('site.compositions', [
            'compositions' => $compositions,
            'selectedCompositions' => $selectedCompositions,
            'employees' => $employees
        ]);
    }

    public function departments(Request $request): string
    {
        $departments = Department::all();
        $showAge = isset($_GET['show_age']); // проверяем, нажата ли кнопка

        if ($showAge) {
            $employees = Employee::all(['id_department', 'birth_date']);
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
            foreach ($departments as $dept) {
                if (isset($avgAge[$dept->id_department])) {
                    $dept->avg_age = round($avgAge[$dept->id_department]['sum'] / $avgAge[$dept->id_department]['cnt']);
                } else {
                    $dept->avg_age = null;
                }
            }
        }

        return (new View())->render('site.department', [
            'departments' => $departments,
            'showAge' => $showAge
        ]);
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

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'working']);
    }
//    public function compositions(Request $request): string
//    {
//        $compositions  = Composition ::all();
//        return (new View())->render('site.composition', ['compositions' => $compositions]);
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
    public function employeeCreate(Request $request): string
    {
        $this->checkHrStaff(); // проверка роли
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'last_name'   => ['required'],
                'first_name'  => ['required'],
                'birth_date'  => ['required', 'date'],
                'id_department'=> ['required']
            ]);
            if ($validator->fails()) {
                return (new View())->render('site.employee_form', [
                    'errors'      => $validator->errors(),
                    'positions'   => Position::all(),
                    'departments' => Department::all()
                ]);
            }
            Employee::create($request->all());
            app()->route->redirect('/employees');
        }
        return (new View())->render('site.employee_form', [
            'positions'   => Position::all(),
            'departments' => Department::all()
        ]);
    }
    private function checkHrStaff()
    {
        if (!app()->auth::check()) {
            app()->route->redirect('/login');
        }
        $role = app()->auth::user()->role;
        if ($role !== 'admin' && $role !== 'hr_staff') {
            app()->route->redirect('/login');
        }
    }
    public function employeesByDepartment(Request $request): string
    {
        $departments = Department::all();
        $selectedDept = $request->get('department_id');
        $employees = [];
        if ($selectedDept) {
            $employees = Employee::where('id_department', $selectedDept)->get();
        }
        return (new View())->render('site.employees_by_dept', [
            'departments' => $departments,
            'selectedDept' => $selectedDept,
            'employees' => $employees
        ]);
    }
}

