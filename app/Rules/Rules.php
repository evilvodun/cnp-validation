<?php
namespace App\Rules;

use App\Rules\RulesInterface;
use App\Rules\CnpValidationinterface;

class Rules implements RulesInterface, CnpValidationinterface
{
    protected int $minMonth = 1;
    protected int $maxMonth = 12;

    protected int $minDay = 1;
    protected int $maxDay = 31;

    protected int $minLocation = 1;
    protected int $maxLocation = 52;

    protected array $control = [2, 7, 9, 1, 4, 6, 3, 5, 8, 2, 7, 9];

    public function validate(array $cnp): bool
    {
        list($year, $month, $day, $location, $orderNumber, $controlNumber) = $this->split($cnp);

        if (!$this->day($day) || !$this->month($month) || !$this->location($location)) {
            return false;
        }

        if (!$this->orderNumber($orderNumber) || !$this->controlNumber($cnp, $controlNumber)) {
            return false;
        }

        if ($month == 2) {
            return $this->checkFebruary($year, $day);
        }

        return true;
    }

    public function month(int $month): bool
    {
        return ($month >= $this->minMonth && $month <= $this->maxMonth);
    }

    public function day(int $day): bool
    {
        return ($day >= $this->minDay && $day <= $this->maxDay);
    }

    public function location(int $location): bool
    {
        return ($location >= $this->minLocation && $location <= $this->maxLocation);
    }

    public function orderNumber(int $orderNumber): bool
    {
        return ($orderNumber > 0);
    }

    public function controlNumber(array $cnp, int $controlNumber): bool
    {

        $result = 0;

        for ($i = 0, $size = count($cnp); $i < ($size - 1); $i++) {
            $result += $cnp[$i] * $this->control[$i];
        }

        $result = ($result % 11);

        if ($result == 10) {
            $result = 1;
        }

        return $result == $controlNumber;
    }

    protected function checkFebruary(int $year, int $day): bool
    {
        $days = ($year % 4) ? 28 : 29;

        return ($day <= $days);
    }

    protected function split(array $cnp): array
    {
        return [
            intval($cnp[1] . $cnp[2]),
            intval($cnp[3] . $cnp[4]),
            intval($cnp[5] . $cnp[6]),
            intval($cnp[7] . $cnp[8]),
            intval($cnp[9] . $cnp[10] . $cnp[11]),
            intval($cnp[12])
        ];
    }
}
