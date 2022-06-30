<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Transaction\CreateTransactionRequest;
use App\Models\Account;
use App\Models\Transaction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    use ApiResponse;

    /**
     * @param CreateTransactionRequest $request
     * @return JsonResponse
     */
    public function store(CreateTransactionRequest $request): JsonResponse
    {
        $validatedDate = $request->validationData();

        $sender = Account::query()->find($validatedDate['payer_id']);

        if ($sender['balance'] < $validatedDate['amount'])
            return $this->error('your amount is not enough', 422);

        $receiver = Account::query()->find($validatedDate['receiver_id']);
        $sender->decrement('balance', $validatedDate['amount']);
        $receiver->increment('balance', $validatedDate['amount']);
        $transaction = Transaction::query()->create($validatedDate);
        $transaction->load(['payer.user', 'receiver.user']);

        return $this->success($transaction, 'transaction created successfully.', 201);
    }

    /**
     * @param Account $account
     * @return JsonResponse
     */
    public function histories(Account $account): JsonResponse
    {
        return $this->success($account->transactions());
    }
}
