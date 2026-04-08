<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class MinAgeValidator extends AbstractValidator
{
    protected string $message = 'Возраст должен быть не менее :min лет';

    public function rule(): bool
    {

        $birthDate = \DateTime::createFromFormat('Y-m-d', $this->value);
        if (!$birthDate) {
            return false;
        }

        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        return $age >= (int)$this->args[0];
    }

    public function validate(): string
    {
        return str_replace(':min', $this->args[0], $this->message);
    }
}