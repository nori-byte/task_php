<?php
namespace Validators;

use Src\Validator\AbstractValidator;  // правильный импорт

class CyrillicValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно содержать только кириллицу';

    public function rule(): bool
    {
        return preg_match('/^[а-яёА-ЯЁ\s\-]+$/u', $this->value) === 1;
    }

    public function validate(): string
    {
        return $this->message;
    }
}