<?php
namespace App\Rules;

class Gender
{
    protected int $min = 1;

    protected int $max = 9;

    protected array $alien = [7, 8, 9];

    protected array $female = [
        2 => 1900,
        4 => 1800,
        6 => 2000,
    ];

    protected array $male = [
        1 => 1900,
        3 => 1800,
        5 => 2000,
    ];

    protected $genericYear = 1900;

    protected int $minYear = 1800;
    protected int $maxYear = 2099;

    public function validate(array $cnp): bool
    {
        $gender = $this->split($cnp);

        if (!$this->genderRange($gender)) {
            return false;
        }

        if ($this->alien($gender, $cnp)) {
            return true;
        }

        switch ($gender) {
            case $gender % 2 == 0:
                return $this->female($gender, $cnp);
                break;

            case $gender % 2 != 0:
                return $this->male($gender, $cnp);
                break;
        }

        return true;
    }

    public function genderRange(int $number): bool
    {
        return ($number >= $this->min && $number <= $this->max);
    }

    public function female(int $gender, array $cnp): bool
    {
        return $this->validateSex($gender, $cnp, 'female');
    }

    public function male(int $gender, array $cnp)
    {
        return $this->validateSex($gender, $cnp, 'male');
    }

    public function alien(int $gender, array $cnp): bool
    {
        if (!in_array($gender, $this->alien)) {
            return false;
        }

        $year = $this->genericYear + $this->year($cnp);

        return ($year > $this->minYear && $year < $this->maxYear);
    }

    protected function split(array $cnp)
    {
        return $cnp[0];
    }

    protected function year(array $cnp): int
    {
        return intval($cnp[1] . $cnp[2]);
    }

    protected function exists(string|int $key, array $array): bool
    {
        return array_key_exists($key, $array);
    }

    protected function validateSex(int $gender, array $cnp, string $control): bool
    {
        if (!$this->exists($gender, $this->{$control})) {
            return false;
        }

        $year = $this->{$control}[$gender] + $this->year($cnp);

        return ($year > $this->minYear && $year < $this->maxYear);
    }
    
}
