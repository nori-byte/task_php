<?php

use Model\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

class SiteTest extends TestCase
{
    #[DataProvider('additionProvider')]
    #[RunInSeparateProcess]
    public function testSignup(string $httpMethod, array $userData, string $expected): void
    {
        $this->initApp();

        if ($userData['login'] === 'login is busy') {
            $firstUser = User::query()->first();
            if ($firstUser) {
                $userData['login'] = $firstUser->login;
            }
        }

        $request = $this->createMock(\Src\Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn($userData);
        $request->method = $httpMethod;

        $controller = new \Controller\Site();
        $result = $controller->signup($request);

        if (!empty($result)) {
            $regex = '/' . preg_quote($expected, '/') . '/';
            $this->expectOutputRegex($regex);
            return;
        }

        $user = User::where('login', $userData['login'])->first();
        $this->assertNotNull($user, 'User not found in DB');
        $user->delete();

        $headers = xdebug_get_headers();
        $this->assertContains($expected, $headers);
    }

    private function initApp(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/task_php';

        $config = [
            'app'   => include $_SERVER['DOCUMENT_ROOT'] . '/config/app.php',
            'db'    => include $_SERVER['DOCUMENT_ROOT'] . '/config/db.php',
            'path'  => include $_SERVER['DOCUMENT_ROOT'] . '/config/path.php',
        ];

        if (isset($config['app']['providers'])) {
            $config['providers'] = $config['app']['providers'];
        }

        // ВАЖНО: создаём объект Settings из массива
        $settings = new \Src\Settings($config);
        // Передаём объект Settings, а не массив
        $app = new \Src\Application($settings);

        // Устанавливаем путь к папке представлений
        if (method_exists(\Src\View::class, 'setPath')) {
            \Src\View::setPath($_SERVER['DOCUMENT_ROOT'] . '/views');
        }

        // Добавляем роутер через статический метод getInstance (обходим приватный конструктор)
        if (class_exists(\Src\Route::class)) {
            if (method_exists(\Src\Route::class, 'getInstance')) {
                $route = \Src\Route::getInstance();
            } else {
                $reflection = new \ReflectionClass(\Src\Route::class);
                $route = $reflection->newInstanceWithoutConstructor();
            }
            $app->route = $route;
        }

        $GLOBALS['app'] = $app;

        if (!function_exists('app')) {
            function app() {
                return $GLOBALS['app'];
            }
        }
    }

    public static function additionProvider(): array
    {
        return [
            'GET empty' => ['GET', ['login' => '', 'password' => ''], '<h3></h3>'],
            'POST empty' => ['POST', ['login' => '', 'password' => ''], '<h3>{"login":["Поле login пусто"],"password":["Поле password пусто"]}</h3>'],
            'POST busy login' => ['POST', ['login' => 'login is busy', 'password' => 'admin'], '<h3>{"login":["Поле login должно быть уникально"]}</h3>'],
            'POST success' => ['POST', ['login' => md5(time()), 'password' => 'admin'], 'Location: /users'],
        ];
    }
}