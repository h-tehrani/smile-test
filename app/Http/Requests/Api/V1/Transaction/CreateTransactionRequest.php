<?php

namespace App\Http\Requests\Api\V1\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CreateTransactionRequest extends FormRequest
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
    #[ArrayShape(['payer_id' => "string", 'receiver_id' => "string", 'amount' => "string"])]
    public function rules(): array
    {
        return [
            'payer_id' => 'required|exists:accounts,id',
            'receiver_id' => 'required|exists:accounts,id|different:payer_id',
            'amount' => 'required|numeric|min:0'
        ];
    }
}
