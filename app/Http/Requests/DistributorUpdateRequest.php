<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DistributorUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'super-admin'], true);
    }

    public function rules(): array
    {
        $distributor = $this->route('distributor');

        return [
            'code'=>['required','string','max:30',Rule::unique('distributors','code')->ignore($distributor)],
            'name'=>['required','string','max:150'],
            'contact_person'=>['nullable','string','max:100'],
            'phone'=>['nullable','string','max:30'],
            'email'=>['nullable','email','max:150'],
            'address'=>['nullable','string','max:2000'],
            'city'=>['nullable','string','max:100'],
            'is_active'=>['nullable','boolean'],
        ];
    }
}