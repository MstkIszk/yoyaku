<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\CheckReservationCount;

class ReserveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'reserved_date' => 'required|date',
            'count' => ['required', 'numeric', new CheckReservationCount($this->reserved_date, $this->count)],
        ];
    }
}
