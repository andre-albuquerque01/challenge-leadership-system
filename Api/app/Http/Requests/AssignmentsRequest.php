<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentsRequest extends FormRequest
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
        $rules = [
            "idMember" => "required",
            "idLeader" => "required",
        ];

        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            $rules["idMember"] = [
                'nullable',
            ];
            $rules["idLeader"] = [
                "nullable",
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            "idMember.required" => "O ID do membro é obrigatório.",
            "idLeader.required" => "O ID do líder é obrigatório.",
        ];
    }
}
