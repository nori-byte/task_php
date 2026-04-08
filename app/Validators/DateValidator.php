<?php
namespace Src\Validator;

class DateValidator extends AbstractValidator
{
    protected function rule(): bool
    {
        $date = \DateTime::createFromFormat('Y-m-d', $this->value);
        return $date && $date->format('Y-m-d') === $this->value;
    }

    protected function validate(): string
    {
        return $this->message ?: 'Поле :field должно быть корректной датой';
    }
}