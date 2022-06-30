<?php

namespace App\Http\Requests\Api\V1\Account;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CreateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['user_id' => "string", 'balance' => "string"])]
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'balance' => 'required|numeric|min:0'
        ];
    }
}
