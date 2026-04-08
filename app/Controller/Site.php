<?php

namespace Controller;

use Model\Department;
use Model\Employee;
use Model\Post;
use Model\Position;
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
        $this->checkHrStaff();
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


//    public function departments(Request $request): string
//    {
//        $departments = Department::all();
//        $selectedDepartments = $_GET['department_ids'] ?? [];
//        if (!is_array($selectedDepartments)) $selectedDepartments = [];
//
//        $showAverageAge = isset($_GET['show_age']) && app()->auth::user() && app()->auth::user()->canManageEmployees();
//
//        $employees = [];
//        $averageAges = [];
//
//        if (!empty($selectedDepartments)) {
//            $employees = Employee::with('department')
//                ->whereIn('id_department', $selectedDepartments)
//                ->get();
//
//            if ($showAverageAge) {
//                $ageData = [];
//                foreach ($employees as $emp) {
//                    if (!$emp->birth_date) continue;
//                    $age = date_diff(date_create($emp->birth_date), date_create('today'))->y;
//                    $deptId = $emp->id_department;
//                    $ageData[$deptId]['sum'] = ($ageData[$deptId]['sum'] ?? 0) + $age;
//                    $ageData[$deptId]['cnt'] = ($ageData[$deptId]['cnt'] ?? 0) + 1;
//                }
//                foreach ($ageData as $deptId => $data) {
//                    $averageAges[$deptId] = round($data['sum'] / $data['cnt']);
//                }
//            }
//        }
//
//        return (new View())->render('site.departments', [
//            'departments' => $departments,
//            'selectedDepartments' => $selectedDepartments,
//            'employees' => $employees,
//            'showAverageAge' => $showAverageAge,
//            'averageAges' => $averageAges
//        ]);
//    }
    public function departments(Request $request): string
    {
        $this->checkHrStaff(); // доступ только HR и админу

        // Обработка POST-запроса на создание нового подразделения
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'type' => ['required'],
            ], [
                'required' => 'Поле :field обязательно'
            ]);

            if (!$validator->fails()) {
                Department::create([
                    'name_department' => $request->name,
                    'view_department' => $request->type,
                ]);
                // После создания перенаправляем на ту же страницу, чтобы избежать повторной отправки
                app()->route->redirect('/departments');
            }
            // Если ошибки, продолжим отображение страницы с сообщением об ошибке
            $error = 'Ошибка валидации: заполните все поля.';
        }

        // GET или после ошибки POST – отображаем список подразделений и форму
        $departments = Department::all();
        $selectedDepartments = $_GET['department_ids'] ?? [];
        if (!is_array($selectedDepartments)) $selectedDepartments = [];

        $showAverageAge = isset($_GET['show_age']) && Auth::check() && (Auth::user()->id_role === 3 || Auth::user()->id_role === 6);

        $employees = [];
        $averageAges = [];

        if (!empty($selectedDepartments)) {
            $employees = Employee::with('department')
                ->whereIn('id_department', $selectedDepartments)
                ->get();

            if ($showAverageAge) {
                $ageData = [];
                foreach ($employees as $emp) {
                    if (!$emp->birth_date) continue;
                    $age = date_diff(date_create($emp->birth_date), date_create('today'))->y;
                    $deptId = $emp->id_department;
                    $ageData[$deptId]['sum'] = ($ageData[$deptId]['sum'] ?? 0) + $age;
                    $ageData[$deptId]['cnt'] = ($ageData[$deptId]['cnt'] ?? 0) + 1;
                }
                foreach ($ageData as $deptId => $data) {
                    $averageAges[$deptId] = round($data['sum'] / $data['cnt']);
                }
            }
        }

        return (new View())->render('site.departments', [
            'departments' => $departments,
            'selectedDepartments' => $selectedDepartments,
            'employees' => $employees,
            'showAverageAge' => $showAverageAge,
            'averageAges' => $averageAges,
            'error' => $error ?? null, // передаём ошибку, если есть
        ]);
    }


    public function index(Request $request): string
    {
        $posts = Post::all();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function employees(Request $request): string
    {
        $this->checkHrStaff();
        $employees  = Employee::all();
        return (new View())->render('site.employee', ['employees' => $employees]);
    }

    public function hello(Request $request): string
    {
        $search = trim($request->get('search', ''));

        $departments = collect();
        $employees   = collect();
        $compositions= collect();
        $positions   = collect();

        if ($search !== '') {
            // Поиск по отделам (name_department, view_department)
            $departments = Department::where('name_department', 'like', "%{$search}%")
                ->orWhere('view_department', 'like', "%{$search}%")
                ->get();

            // Поиск по сотрудникам (фамилия, имя, отчество)
            $employees = Employee::where('last_name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->get();

            // Поиск по составам (название)
            $compositions = Composition::where('composition_name', 'like', "%{$search}%")->get();

            // Поиск по должностям
            $positions = Position::where('position_name', 'like', "%{$search}%")->get();
        }

        return (new View())->render('site.hello', [
            'message'      => 'working',
            'search'       => $search,
            'departments'  => $departments,
            'employees'    => $employees,
            'compositions' => $compositions,
            'positions'    => $positions,
        ]);
    }

//    public function signup(Request $request): string
//    {
//        if ($request->method === 'POST') {
//
//            $validator = new Validator($request->all(), [
//                'name' => ['required'],
//                'login' => ['required', 'unique:users,login'],
//                'password' => ['required']
//            ], [
//                'required' => 'поле :field пусто',
//                'unique' => 'поле :field должно быть уникально'
//            ]);
//
//            if($validator->fails()){
//                // Собираем все ошибки в одну строку
//                $errorMessages = [];
//                foreach ($validator->errors() as $field => $messages) {
//                    $errorMessages[] = implode(', ', $messages);
//                }
//                $message = implode(' и ', $errorMessages);
//                return new View('site.signup', ['message' => $message]);
//            }
//
//            if (User::create($request->all())) {
//                app()->route->redirect('/login');
//            }
//        }
//        return new View('site.signup');
//    }
//    public function signup(Request $request): string
//    {
//        if ($request->method === 'POST') {
//            $validator = new Validator($request->all(), [
//                'name' => ['required'],
//                'login' => ['required', 'unique:users,login'],
//                'password' => ['required']
//            ], [
//                'required' => 'поле :field пусто',
//                'unique' => 'поле :field должно быть уникально'
//            ]);
//
//            if($validator->fails()){
//                $errorMessages = [];
//                foreach ($validator->errors() as $field => $messages) {
//                    $errorMessages[] = implode(', ', $messages);
//                }
//                $message = implode(' и ', $errorMessages);
//                return new View('site.signup', ['message' => $message]);
//            }
//
//            // Добавляем роль по умолчанию - hr_staff
//            $data = $request->all();
//            $data['role'] = 'hr_staff';
//
//            if (User::create($data)) {
//                app()->route->redirect('/login');
//            }
//        }
//        return new View('site.signup');
//    }



    public function signup(Request $request): string
    {
        $this->checkAdmin();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name'     => ['required'],
                'login'    => ['required', 'unique:users,login'],
                'password' => ['required']
            ], [
                'required' => 'Поле :field пусто',
                'unique'   => 'Поле :field должно быть уникально'
            ]);

            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors() as $field => $messages) {
                    $errors[] = implode(', ', $messages);
                }
                $message = implode(' и ', $errors);
                return (new View())->render('site.signup', ['message' => $message]);
            }

            $data = $request->all();
            $data['id_role'] = 6;
            $data['password'] = md5($data['password']);

            if (User::create($data)) {
                app()->route->redirect('/login');
            }
        }
        return (new View())->render('site.signup');
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

    public function users(Request $request): string
    {
        $this->checkAdmin();
        $users = User::all();
        return (new View())->render('site.users', ['users' => $users]);
    }

    public function employeeCreate(Request $request): string
    {
        $this->checkHrStaff();
        if ($request->method === 'POST') {
//            $validator = new Validator($request->all(), [
//                'last_name'    => ['required' => true],
//                'first_name'   => ['required' => true],
//                'birth_date'   => ['required' => true, 'date' => true],
//                'id_department'=> ['required' => true]
//            ]);
            $validator = new Validator($request->all(), [
                'last_name'     => ['required'],
                'first_name'    => ['required'],
                'birth_date'    => ['required', 'date', 'min_age:14'],
                'id_department' => ['required', 'exists:departments,id_department'],
            ]);
            if ($validator->fails()) {
                return (new View())->render('site.employee_form', [
                    'errors'      => $validator->errors(),
                    'positions'   => Position::all(),
                    'departments' => Department::all(),
                    'compositions'=> Composition::all()
                ]);
            }
            // Удаляем csrf_token и сохраняем
            $data = $request->all();
            unset($data['csrf_token']);
            Employee::create($data);
            app()->route->redirect('/employees');
        }
        return (new View())->render('site.employee_form', [
            'positions'   => Position::all(),
            'departments' => Department::all(),
            'compositions'=> Composition::all()
        ]);
    }
    private function checkHrStaff()
    {
        if (!Auth::check()) {
            app()->route->redirect('/login');
        }
        $user = Auth::user();
        // Разрешены роли: admin и hr_staff
        if (!in_array($user->role->role_name, [ 'hr_staff'])) {
            echo (new View())->render('site.access_denied');
            exit;
        }
    }

    private function checkAdmin()
    {
        if (!Auth::check()) {
            app()->route->redirect('/login');
        }
        if (Auth::user()->role->role_name !== 'admin') {
            echo (new View())->render('site.access_denied');
            exit;
        }
    }


}


