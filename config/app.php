<?php
return [
    //Класс аутентификации
    'auth' => \Src\Auth\Auth::class,
    //Клас пользователя
    'identity' => \Model\User::class,
    //Классы для middleware
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
    ],
    'validators' => [
        'required' => \ValidatorPackage\Validator\RequireValidator::class,
        'unique'   => \ValidatorPackage\Validator\UniqueValidator::class,
        'min_age'  => \ValidatorPackage\Validators\MinAgeValidator::class,
        'date'     => \ValidatorPackage\Validators\DateValidator::class,
        'cyrillic' => \ValidatorPackage\Validators\CyrillicValidator::class,
    ],
    'providers' => [
        'kernel' => Providers\KernelProvider::class,
        'route' => Providers\RouteProvider::class,
        'db' => Providers\DBProvider::class,
        'auth' => Providers\AuthProvider::class,
    ],
    'routeAppMiddleware' => [
        'csrf' => \Middlewares\CSRFMiddleware::class,
        'trim' => \Middlewares\TrimMiddleware::class,
        'json' => \Middlewares\JSONMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
    ],


];