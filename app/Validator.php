<?php

namespace App;

use App\Rules\Rules;
use App\Rules\Gender;

class Validator
{
    protected int $length = 13;
    protected Gender $gender;
    protected Rules $rules;

    public function __construct(Gender $gender, Rules $rules)
    {
        $this->gender = $gender;
        $this->rules = $rules;
    }

    public function isCnpValid(string $cnp): bool
    {
        if (!$this->isNumeric($cnp) || !$this->isValidLength($cnp)) {
            return false;
        }
        
        if (!$this->gender->validate($this->convert($cnp))) {
            return false;
        }

        if (!$this->rules->validate($this->convert($cnp))) {
            return false;
        }

        return true;
    }

    public function isNumeric(string $cnp): bool
    {
        return is_numeric($cnp);
    }

    public function isValidLength(string $cnp): bool
    {
        return strlen($cnp) == $this->length;
    }

    public function convert(string $cnp): array
    {
        return str_split($cnp);
    }
}
