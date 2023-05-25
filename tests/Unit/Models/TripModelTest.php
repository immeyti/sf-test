<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\Trip;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TripModelTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_tripe_belongs_to_a_order()
    {
        $order = Order::factory()->create();
        $trip = Trip::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(Order::class, $trip->order);
        $this->assertEquals(1, $trip->order->count());
    }
}
