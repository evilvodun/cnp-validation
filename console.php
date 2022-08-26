<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Validator;
use App\Rules\Rules;
use App\Rules\Gender;

if (empty($argv[1])) {
    echo "Trebuie sa scrii un cnp";
    exit();
}

$cnp = $argv[1];

$validator = new Validator(
    new Gender(),
    new Rules()
);

$valid = $validator->isCnpValid($cnp);

if ($valid) {
    echo "CNP Valid\n";
    exit();
}

echo "CNP Invalid\n";
