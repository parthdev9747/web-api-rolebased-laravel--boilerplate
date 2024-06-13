<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() === "POST") {
            return [
                'name' => 'required|unique:customers,name',
                'point_of_contact_name' => 'nullable',
                'point_of_contact_email' => 'nullable|email',
                'company_website' => 'nullable|url',
                'country' => 'required|integer',
                'currency' => 'required|integer',
            ];
        } else {

            $customerId = $this->route('customer');

            return [
                'name' => 'required|unique:customers,name,' . $customerId,
                'point_of_contact_name' => 'nullable',
                'point_of_contact_email' => 'nullable|email',
                'company_website' => 'nullable|url',
                'country' => 'required|integer',
                'currency' => 'required|integer',
            ];
        }
    }
}
