<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Reserve;

class CheckReservationCount implements Rule
{
    protected $date;
    protected $newCount;

    public function __construct($date, $newCount)
    {
        $this->date = $date;
        $this->newCount = $newCount;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function passes($attribute, $value)
    {
        $existingCount = Reserve::where('ReserveDate', $this->date)->count();
        $total = $existingCount + $this->newCount;
        return ($total <= Reserve::$MaxReserve);
    }
    public function message()
    {
        return '予約可能枠が不足しています。';
    }
}
