<?php

namespace App\Rules;

interface RulesInterface
{
    public function validate(array $array): bool;
}
