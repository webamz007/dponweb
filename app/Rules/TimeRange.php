<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimeRange implements Rule
{
    public function passes($attribute, $value)
    {
        [$startTime, $endTime] = $value;

        // Ensure start time is not greater than end time
        if (strtotime($startTime) >= strtotime($endTime)) {
            return false;
        }

        // Ensure start time is not the same as end time
        if ($startTime === $endTime) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'The start time must be earlier than the end time, and they cannot be the same.';
    }
}
