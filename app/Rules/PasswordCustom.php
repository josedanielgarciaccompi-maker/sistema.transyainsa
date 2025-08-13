<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class PasswordCustom implements Rule
{
    protected $min;
    protected $requireUppercase;
    protected $requireNumeric;
    protected $requireSpecial;
    protected $message;

    public function __construct($min = 8, $requireUppercase = true, $requireNumeric = true, $requireSpecial = true)
    {
        $this->min = $min;
        $this->requireUppercase = $requireUppercase;
        $this->requireNumeric = $requireNumeric;
        $this->requireSpecial = $requireSpecial;
        $this->message = 'La contraseña debe tener al menos :min caracteres, :uppercaseuna mayúscula, :numericun número y :specialun carácter especial.';
    }

    public function passes($attribute, $value)
    {
        if (strlen($value) < $this->min) {
            $this->message = 'La contraseña debe tener al menos ' . $this->min . ' caracteres.';
            return false;
        }
        if ($this->requireUppercase && !preg_match('/[A-Z]/', $value)) {
            $this->message = 'La contraseña debe contener al menos una letra mayúscula.';
            return false;
        }
        if ($this->requireNumeric && !preg_match('/[0-9]/', $value)) {
            $this->message = 'La contraseña debe contener al menos un número.';
            return false;
        }
        if ($this->requireSpecial && !preg_match('/[^a-zA-Z0-9]/', $value)) {
            $this->message = 'La contraseña debe contener al menos un carácter especial.';
            return false;
        }
        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
