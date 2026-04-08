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
use ValidatorPackage\Validator\Validator;

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

    public function departments(Request $request): string
    {
        $this->checkHrStaff();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'cyrillic'],
                'type' => ['required', 'cyrillic'],
            ], [
                'required' => 'Поле :field обязательно',
                'cyrillic' => 'Поле :field должно быть на кириллице'
            ]);

            if (!$validator->fails()) {
                Department::create([
                    'name_department' => $request->name,
                    'view_department' => $request->type,
                ]);
                app()->route->redirect('/departments');
            }
            $error = 'Ошибка валидации: заполните все поля.';
        }

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
            'error' => $error ?? null,
        ]);
    }


    public function index(Request $request): string
    {
        $posts = Post::all();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

//    public function employees(Request $request): string
//    {
//        $this->checkHrStaff();
//        $employees  = Employee::all();
//        return (new View())->render('site.employee', ['employees' => $employees]);
//    }
    public function employees(Request $request): string
    {
        $this->checkHrStaff();

        if ($request->method === 'POST') {
            $data = $request->all();

            if (isset($data['id_employee'], $data['id_department'])) {
                $employee = Employee::find($data['id_employee']);
                if ($employee) {
                    $employee->id_department = $data['id_department'] ?: null;
                    $employee->save();
                    $_SESSION['flash'] = " Сотрудник прикреплён к подразделению.";
                } else {
                    $_SESSION['flash'] = " Ошибка: сотрудник не найден (ID {$data['id_employee']}).";
                }
            } else {
                $_SESSION['flash'] = " Ошибка: не переданы ID сотрудника или подразделения.";
            }


            app()->route->redirect('/employees');
            return '';
        }

        $employees = Employee::with('department')->get();
        $departments = Department::all();
        return (new View())->render('site.employee', [
            'employees' => $employees,
            'departments' => $departments
        ]);
    }

    public function hello(Request $request): string
    {
        $search = trim($request->get('search', ''));

        $departments = collect();
        $employees   = collect();
        $compositions= collect();
        $positions   = collect();

        if ($search !== '') {
            $departments = Department::where('name_department', 'like', "%{$search}%")
                ->orWhere('view_department', 'like', "%{$search}%")
                ->get();

            $employees = Employee::where('last_name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('birth_date', 'like', "%{$search}%")
                ->get();

            $compositions = Composition::where('composition_name', 'like', "%{$search}%")->get();

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
//        $this->checkAdmin();
//
//        if ($request->method === 'POST') {
//            $validator = new Validator($request->all(), [
//                'name'     => ['required'],
//                'login'    => ['required', 'unique:users,login'],
//                'password' => ['required']
//            ], [
//                'required' => 'Поле :field пусто',
//                'unique'   => 'Поле :field должно быть уникально'
//            ]);
//
//            if ($validator->fails()) {
//                $errors = [];
//                foreach ($validator->errors() as $field => $messages) {
//                    $errors[] = implode(', ', $messages);
//                }
//                $message = implode(' и ', $errors);
//                return (new View())->render('site.signup', ['message' => $message]);
//            }
//
//            $data = $request->all();
//            $data['id_role'] = 6;
//            $data['password'] = md5($data['password']);
//
//            if (User::create($data)) {
//                app()->route->redirect('/login');
//            }
//        }
//        return (new View())->render('site.signup');
//    }
    public function signup(Request $request): string
    {
        $this->checkAdmin();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name'     => ['required', 'cyrillic'],
                'login'    => ['required', 'unique:users,login'],
                'password' => ['required', 'cyrillic']
            ], [
                'required' => 'Поле :field пусто',
                'unique'   => 'Поле :field должно быть уникально',
                'cyrillic' => ' :field должно содержать только кириллицу',
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

                $_SESSION['flash'] = " Пользователь " . htmlspecialchars($data['login']) . " успешно зарегистрирован.";
                app()->route->redirect('/users');
            } else {
                $_SESSION['flash'] = "Ошибка при регистрации пользователя.";
                app()->route->redirect('/signup');
            }
        }
        return (new View())->render('site.signup');
    }
    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
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
            $validator = new Validator($request->all(), [
                'last_name'     => ['required', 'cyrillic'],
                'first_name'    => ['required', 'cyrillic'],
                'middle_name'   => ['cyrillic'], // необязательно
                'birth_date'    => ['required', 'date', 'min_age:14'],
            ], [
                'required' => 'Поле :field пусто',
                'cyrillic' => 'Поле :field должно содержать только кириллицу',
                'min_age'  => 'Возраст должен быть не менее 14 лет',
            ]);

            if ($validator->fails()) {
                $fieldNames = [
                    'last_name'   => 'Фамилия',
                    'first_name'  => 'Имя',
                    'middle_name' => 'Отчество',
                    'birth_date'  => 'Дата рождения',
                ];

                $errors = $validator->errors();
                $errorMessages = [];

                foreach ($errors as $field => $messages) {
                    $ruField = $fieldNames[$field] ?? $field;
                    foreach ($messages as $msg) {
                        $msg = str_replace($field, $ruField, $msg);
                        $msg = str_replace(':field', $ruField, $msg);
                        $errorMessages[] = $msg;
                    }
                }

                $message = implode(' и ', $errorMessages);

                return (new View())->render('site.employee_form', [
                    'message'     => $message,
                    'positions'   => Position::all(),
                    'departments' => Department::all(),
                    'compositions'=> Composition::all()
                ]);
            }

            $data = $request->all();
            unset($data['csrf_token']);
            Employee::create($data);

            $_SESSION['flash'] = "Сотрудник успешно добавлен.";
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


