<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
            "sender_id.required" => "O rementent é obrigatório.",
            "receiver_id.required" => "O destinatário é obrigatório.",
            "content.required" => "O conteúdo é obrigatório.",
        ];
    }
}
