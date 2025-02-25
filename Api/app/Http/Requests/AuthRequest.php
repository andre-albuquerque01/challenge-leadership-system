<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "O e-mail é obrigatório.",
            "email.email" => "O e-mail informado não é válido.",
            "email.max" => "O e-mail não pode ter mais de 255 caracteres.",
            "email.min" => "O e-mail deve ter pelo menos 2 caracteres.",

            "password.required" => "A senha é obrigatória.",
            "password.confirmed" => "A confirmação da senha não corresponde.",
            "password.min" => "A senha deve ter pelo menos 8 caracteres.",
            "password.mixedCase" => "A senha deve conter pelo menos uma letra maiúscula e uma minúscula.",
            "password.letters" => "A senha deve conter pelo menos uma letra.",
            "password.numbers" => "A senha deve conter pelo menos um número.",
            "password.symbols" => "A senha deve conter pelo menos um símbolo.",
            "password.uncompromised" => "A senha escolhida já apareceu em vazamentos de dados. Escolha outra senha.",
        ];
    }
}
