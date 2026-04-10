<?php
namespace Controller;


use Model\Department;
use Model\Employee;
use Model\User;
use Src\Request;
use Src\View;
use Src\Auth\Auth;

class Api
{
    public function index(): void
    {
        $students = Employee::all()->toArray();

        (new View())->toJSON($students);
    }

    public function echo(Request $request): void
    {
        (new View())->toJSON($request->all());
    }

    public function api_auth(Request $request)
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::where('login', $login)->first();

        if (!$user) {
            http_response_code(401);
            (new View())->toJSON(['error' => 'User not found']);
            return;
        }

        if ($user->password !== md5($password)) {
            http_response_code(401);
            (new View())->toJSON([
                'error' => 'Password mismatch'
            ]);
            return;
        }

        $token = bin2hex(random_bytes(32));
        $user->api_token = $token;
        $user->save();

        (new View())->toJSON(['token' => $token]);
    }

    public function bearer_token(Request $request): void
    {
        (new View())->toJSON($request->all());
    }

    public function employeesGroupedByDepartment(Request $request): void
    {
        // Проверка токена
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if ($authHeader !== 'Bearer token') {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        // Основная логика
        $employees = Employee::with('department')->get();
        $grouped = [];
        foreach ($employees as $emp) {
            $deptId = $emp->id_department;
            $deptName = $emp->department ? $emp->department->name_department : 'Без подразделения';
            if (!isset($grouped[$deptId])) {
                $grouped[$deptId] = [
                    'department_id'   => $deptId,
                    'department_name' => $deptName,
                    'employees'       => []
                ];
            }
            $grouped[$deptId]['employees'][] = [
                'id'          => $emp->id_employee,
                'full_name'   => trim($emp->last_name . ' ' . $emp->first_name . ' ' . $emp->middle_name),
                'birth_date'  => $emp->birth_date,
                'position'    => $emp->position ? $emp->position->position_name : null,
                'composition' => $emp->composition ? $emp->composition->composition_name : null,
            ];
        }
        $result = array_values($grouped);
        (new View())->toJSON($result);
    }
}