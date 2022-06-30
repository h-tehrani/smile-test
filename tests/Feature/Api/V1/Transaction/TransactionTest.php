<?php

namespace Tests\Feature\Api\V1\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @return void
     */
    public function test_create_transaction()
    {
        $balance = round(rand(10000, 99999) / 3,2);
        $transferAmount = round(rand(1, 9999) / 3,2);
        $payer = Account::factory(['balance' => $balance])->create();
        $receiver = Account::factory(['balance' => $balance])->create();
        $input = [
            'payer_id' => $payer['id'],
            'receiver_id' => $receiver['id'],
            'amount' => $transferAmount,
        ];
        $response = $this->post('/api/v1/transactions/store', $input);
        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', $input);
        /**
         * @var Account $payer
         */
        $payer = Account::query()->find($payer['id']);

        $this->assertEquals($balance - $transferAmount, $payer->balance);
        /**
         * @var Account $receiver
         */
        $receiver = Account::query()->find($receiver['id']);
        $this->assertEquals($balance + $transferAmount, $receiver->balance);
    }

    /**
     * @return void
     */
    public function test_transaction_can_not_be_created_when_transaction_amount_is_bigger_than_payer_balance()
    {
        $balance = round(rand(1000, 9999) / 3,2);
        $transferAmount = round(rand(10000, 99999) / 3,2);
        $payer = Account::factory(['balance' => $balance])->create();
        $receiver = Account::factory(['balance' => $balance])->create();

        $response = $this->post('/api/v1/transactions/store', [
            'payer_id' => $payer['id'],
            'receiver_id' => $receiver['id'],
            'amount' => $transferAmount,
        ], [
            'accept' => 'application/json'
        ]);

        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_create_transaction_with_invalid_data()
    {
        $response = $this->post('/api/v1/transactions/store', [
            'payer_id' => 100, /*does not exist*/
            'receiver_id' => 101, /*does not exist*/
            'amount' => 'invalid amount',
        ], [
            'accept' => 'application/json'
        ]);
        $response->assertJsonValidationErrors([
            'payer_id',
            'receiver_id',
            'amount',
        ]);
        $balance = round(rand(10000, 99999) / 3,2);
        $transferAmount = round(rand(1, 9999) / 3,2);
        $payer = Account::factory(['balance' => $balance])->create();
        $receiver = Account::factory(['balance' => $balance])->create();

        // amount should not be a negative number
        $response = $this->post('/api/v1/transactions/store', [
            'payer_id' => $payer['id'], /*does not exist*/
            'receiver_id' => $receiver['id'], /*does not exist*/
            'amount' => -1, // invalid amount value,
        ], [
            'accept' => 'application/json'
        ]);

        $response->assertJsonValidationErrors([
            'amount',
        ]);

        // payer and receiver can not be the same
        $response = $this->post('/api/v1/transactions/store', [
            'payer_id' => $payer['id'],
            'receiver_id' => $payer['id'],
            'amount' => $transferAmount,
        ], [
            'accept' => 'application/json'
        ]);

        $response->assertJsonValidationErrors([
            'receiver_id',
        ]);
    }

    /**
     * @return void
     */
    public function test_history_api_can_fetch_given_account_history()
    {
        $balance = round(rand(1000, 100000) / 3,2);
        $transferAmount = round(rand(10, 1000) / 3,2);
        $firstAccount = Account::factory(['balance' => $balance])->create();
        $secondAccount = Account::factory(['balance' => $balance])->create();
        Transaction::factory([
            'payer_id' => $firstAccount['id'],
            'receiver_id' => $secondAccount['id'],
            'amount' => $transferAmount
        ])->count(10)->create();

        Transaction::factory([
            'payer_id' => $secondAccount['id'],
            'receiver_id' => $firstAccount['id'],
            'amount' => $transferAmount
        ])->count(8)->create();

        $response = $this->get('/api/v1/transactions/history/' . $firstAccount['id']);
        $response->assertStatus(200);
        /**
         * @var Account $account
         */
        $account = Account::query()->find($firstAccount['id']);
        $this->assertEquals($account->transactions()->toArray(), $response->json()['data']);
    }
}
