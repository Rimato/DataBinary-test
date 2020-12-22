<?php

class WeekDayCalculator
{

    private const START_YEAR = 1971;
    private const DAY_IN_YEAR = 365;

    private const DAY_IN_MONTH = [
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    ];

    private const WEEK_DAYS_SHIFTED = [
        0 => 'Thursday',
        1 => 'Friday',
        2 => 'Saturday',
        3 => 'Sunday',
        4 => 'Monday',
        5 => 'Tuesday',
        6 => 'Wednesday'
    ];

    private  $day;
    private $month;
    private $year;

    public function __construct(int $day , int $month, int $year)
    {
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }

    private function validateInput()
    {
        if (($this->year <= 1970) || ($this->year >= 2100)) {
            return false;
        }

        if (($this->month > 12) || ($this->month < 0)) {
            return  false;
        }

        if ($this->isLeapYear() && ($this->month === 2) && $this->day = 29) {
            return true;
        }

        if ($this->day > self::DAY_IN_MONTH[$this->month]) {
            return false;
        }

        return true;
    }

    /**
     * @return false|string
     */
    public function calculateInEasyWay()
    {
        return date('l', strtotime("$this->year-$this->month-$this->day"));
    }

    /**
     * @return string
     */
    public function calculateWeekDay(): string
    {
        if (!$this->validateInput()) {
            throw new InvalidArgumentException('Invalid input format');
        }
        $totalDaysPassed = $this->calculateDaysInYears() + $this->calculateDaysInMonth() + $this->day;
        $weekDayIndex = $totalDaysPassed % 7;
        return self::WEEK_DAYS_SHIFTED[$weekDayIndex];
    }

    /**
     * @param int $yearsPassed
     * @return int
     */
    private function calculateLeapYears(int $yearsPassed): int
    {
        $shift = (self::START_YEAR - 1) % 4;
        return intdiv($yearsPassed + $shift, 4);
    }

    /**
     * @return bool
     */
    private function isLeapYear(): bool
    {
        return ($this->year) % 4 === 0;
    }

    /**
     * @return int
     */
    private function calculateDaysInYears(): int
    {
        $yearsPassed = $this->year - self::START_YEAR;

        if ($yearsPassed === 0) {
            return 0;
        }
        $leapYears = $this->calculateLeapYears($yearsPassed);
        return $yearsPassed * self::DAY_IN_YEAR + $leapYears;
    }

    /**
     * @return int|mixed
     */
    private function calculateDaysInMonth(): int
    {
        if ($this->month === 1) {
            return 0;
        }

        $leapYearAppend = $this->isLeapYear() ? 1 : 0;
        $monthPassed = array_slice(self::DAY_IN_MONTH,0, $this->month -1);

        return array_reduce($monthPassed, function ($total, $inMonth) {
           $total += $inMonth;
           return $total;
        }) + $leapYearAppend;
    }
}

$dayCalculator = new WeekDayCalculator(21, 12, 1973);
echo $dayCalculator->calculateWeekDay() . PHP_EOL;
echo $dayCalculator->calculateInEasyWay();

