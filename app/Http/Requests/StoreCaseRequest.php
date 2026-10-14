<?php

namespace App\Http\Requests;

use App\Models\MedicalCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('create', MedicalCase::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'case_number' => ['required', 'string', 'max:255', 'unique:medical_cases,case_number'],
            'patient_reference' => ['required', 'string', 'max:255'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(MedicalCase::STATUSES)],
            'priority' => ['required', 'string', Rule::in(MedicalCase::PRIORITIES)],
            'surgery_date' => ['nullable', 'date'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'case_number.required' => 'Case Number is required.',
            'case_number.unique' => 'This Case Number is already registered in the system.',
            'patient_reference.required' => 'Patient Reference dummy code is required.',
            'surgeon_name.required' => 'Surgeon Name is required.',
            'implant_type.required' => 'Implant Type is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Selected status is invalid.',
            'priority.required' => 'Priority is required.',
            'priority.in' => 'Selected priority is invalid.',
            'surgery_date.date' => 'Surgery Date must be a valid date.',
        ];
    }
}