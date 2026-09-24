<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourierUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'super-admin'], true);
    }

    public function rules(): array
    {
        $courier = $this->route('courier');

        return [
            'code'=>['required','string','max:30',Rule::unique('couriers','code')->ignore($courier)],
            'name'=>['required','string','max:150'],
            'phone'=>['nullable','string','max:30'],
            'email'=>['nullable','email','max:150'],
            'address'=>['nullable','string','max:2000'],
            'vehicle_type'=>['nullable','string','max:50'],
            'vehicle_number'=>['nullable','string','max:30'],
            'is_active'=>['nullable','boolean'],
        ];
    }
}