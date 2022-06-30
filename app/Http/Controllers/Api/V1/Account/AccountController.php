<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Account\CreateAccountRequest;
use App\Models\Account;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    use ApiResponse;

    /**
     * @param CreateAccountRequest $request
     * @return JsonResponse
     */
    public function store(CreateAccountRequest $request): JsonResponse
    {
        $validatedDate = $request->validationData();
        $account = Account::query()->create($validatedDate);
        return $this->success($account, 'Account created successfully.', 201);
    }

    /**
     * @param Account $account
     * @return JsonResponse
     */
    public function show(Account $account): JsonResponse
    {
        $account->load('user');
        return $this->success($account);
    }
}
