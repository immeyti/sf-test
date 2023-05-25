<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_client_have_many_orders()
    {
        $orderCounts = fake()->randomNumber();

        $client = User::factory()
            ->client()
            ->hasOrders($orderCounts)
            ->create();

        $this->assertEquals($orderCounts, $client->orders->count());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $client->orders);
    }
}
