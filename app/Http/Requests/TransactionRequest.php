<?php

namespace App\Http\Requests;

use App\Enum\TransactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
            "valor" => "required|integer",
            "tipo" => ['required',Rule::enum(TransactionType::class)],
            "descricao" => "required|string|max:10|min:1",
        ];
    }
}
