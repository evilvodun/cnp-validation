<?php
namespace App\Rules;

interface RulesInterface
{
    public function month(int $month): bool;

    public function day(int $day): bool;

    public function location(int $location): bool;

    public function orderNumber(int $orderNumber): bool;

    public function controlNumber(array $cnp, int $controlNumber);
}