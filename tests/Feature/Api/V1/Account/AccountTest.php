<?php

namespace Tests\Feature\Api\V1\Account;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_create_account_for_a_user_with_balance()
    {
        $user = User::factory()->create();
        $input = [
            'user_id' => $user['id'],
            'balance' => rand(1, 99999),
        ];
        $response = $this->post('/api/v1/accounts/store', $input);
        $response->assertStatus(201);
        $this->assertDatabaseHas('accounts', $input);
    }

    /**
     * @return void
     */
    public function test_create_account_with_invalid_data()
    {
        $response = $this->post('/api/v1/accounts/store', [
            'user_id' => 100, /*does not exist*/
            'balance' => 'invalid value'
        ], [
            'accept' => 'application/json'
        ]);
        $response->assertJsonValidationErrors([
            'user_id',
            'balance'
        ]);
        $response = $this->post('/api/v1/accounts/store', [
            'user_id' => 100, /*does not exist*/
            'balance' => -12 /*can not fill fewer than zero*/,
        ], [
            'accept' => 'application/json'
        ]);
        $response->assertJsonValidationErrors([
            'user_id',
            'balance'
        ]);
    }

    /**
     * @return void
     */
    public function test_show_account()
    {
        $account = Account::factory()->create();
        $response = $this->get('/api/v1/accounts/show/' . $account['id']);
        $response->assertStatus(200);
    }
}
