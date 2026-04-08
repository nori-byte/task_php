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
//    'validators' => [
//        'required' => \Validators\RequireValidator::class,
//        'unique' => \Validators\UniqueValidator::class,
//        'min_age' => \Validators\MinAgeValidator::class,
//        'date'     => \Src\Validator\DateValidator::class,
//        'cyrillic' => \Validators\CyrillicValidator::class
//    ],
    'validators' => [
        'required' => \ValidatorPackage\Validator\RequireValidator::class,
        'unique'   => \ValidatorPackage\Validator\UniqueValidator::class,
        'min_age'  => \ValidatorPackage\Validators\MinAgeValidator::class,
        'date'     => \ValidatorPackage\Validators\DateValidator::class,
        'cyrillic' => \ValidatorPackage\Validators\CyrillicValidator::class,
    ],
    'routeAppMiddleware' => [
        'csrf' => \Middlewares\CSRFMiddleware::class,
        'trim' => \Middlewares\TrimMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
    ],

];