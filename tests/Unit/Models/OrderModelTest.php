<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_order_has_a_trip()
    {
        $order = Order::factory()->create();
        Trip::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(Trip::class, $order->trip);
        $this->assertEquals(1, $order->trip->count());
    }
}
