<?php

namespace Tests\Feature;


use App\Events\OrderDelayed;
use App\Models\DelayReports;
use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DelayReportTest extends TestCase
{
    use DatabaseMigrations;

    public function test_new_estimate_on_order_that_has_trip()
    {
        //prepare data
        $user = User::factory()
            ->client()
            ->create();

        $order = Order::factory()
            ->for($user, 'client')
            ->has(Trip::factory()->validToNewEstimate())
            ->create([
                'delivery_time' => 50
            ]);

        // mocking get new estimate
        Http::fake([
            'http://run.mocky.io/v3/122c2796-5df4-461c-ab75-87c1192b17f7' =>
                Http::response(["status" => true, "data" => [ "eta" => 15 ]], 200),
        ]);

        //action
        $response = $this->actingAs($user)
            ->post('/api/delay-report/'. $order->id, [
                'orderId' => $order->id,
            ]);


        //asserts
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id,
                    'delivery_time' => 50 + 15,
                ]
            ]);

        // update order delivery_time
        $this->assertDatabaseHas(Order::class, [
            'id' => $order->id,
            'delivery_time' => 50 + 15 // old time + new estimate
        ]);

        // insert a row in delay_reports
        $this->assertDatabaseHas(DelayReports::class, [
            'order_id' => $order->id,
            'time' => 15
        ]);
    }
}