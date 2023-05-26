<?php

namespace Tests\Feature;


use App\Events\OrderDelayed;
use App\Models\DelayReport;
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

    public function test_delay_request_on_order_that_has_trip()
    {
        //prepare data
        $user = $this->createClient();

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

        $this->travel(52)->minutes();

        //action
        $response = $this->actingAs($user)
            ->post('/api/clients/delay-report/'. $order->id);


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
        $this->assertDatabaseHas(DelayReport::class, [
            'order_id' => $order->id,
            'time' => 15
        ]);
    }

    public function test_a_client_cannot_send_delay_request_on_other_client_order()
    {
        $client = $this->createClient();
        $otherClient = $this->createClient();
        $order = Order::factory()
            ->for($otherClient, 'client')
            ->has(Trip::factory()->validToNewEstimate())
            ->create();


        $response = $this->actingAs($client)
            ->post('/api/clients/delay-report/'. $order->id);

        $response->assertStatus(403);
    }

    public function test_delay_process_cannot_start_before_order_delivery_time()
    {
        //prepare data
        $user = $this->createClient();
        $order = Order::factory()
            ->for($user, 'client')
            ->has(Trip::factory()->validToNewEstimate())
            ->create([
                'delivery_time' => 50
            ]);


        //action
        $response = $this->actingAs($user)
            ->post('/api/clients/delay-report/'. $order->id);

        $response->assertStatus(400);

        // insert a row in delay_reports
        $this->assertDatabaseCount(DelayReport::class, 0);

        // update order delivery_time
        $this->assertDatabaseHas(Order::class, [
            'id' => $order->id,
            'delivery_time' => 50
        ]);
    }

    public function test_delay_request_on_order_that_has_not_trip()
    {
        $user = $this->createClient();
        $order = Order::factory()
            ->for($user, 'client')
            ->create([
                'delivery_time' => 50
            ]);

        $this->travel(52)->minutes();


        //action
        $response = $this->actingAs($user)
            ->post('/api/clients/delay-report/'. $order->id);

        // update order delivery_time
        $this->assertDatabaseHas(Order::class, [
            'id' => $order->id,
            'delivery_time' => 50
        ]);

        // insert a row in delay_reports
        $this->assertDatabaseHas(DelayReport::class, [
            'order_id' => $order->id,
            'time' => 0
        ]);

        // TODO:: write some assertion to check queue
    }

    public function test_delay_request_on_order_that_has_not_valid_trip_status_to_calculate_new_estimate()
    {
        $user = $this->createClient();
        $order = Order::factory()
            ->for($user, 'client')
            ->has(Trip::factory()->notValidToNewEstimate())
            ->create([
                'delivery_time' => 50
            ]);

        $this->travel(52)->minutes();


        //action
        $response = $this->actingAs($user)
            ->post('/api/clients/delay-report/'. $order->id);

        // update order delivery_time
        $this->assertDatabaseHas(Order::class, [
            'id' => $order->id,
            'delivery_time' => 50
        ]);

        // insert a row in delay_reports
        $this->assertDatabaseHas(DelayReport::class, [
            'order_id' => $order->id,
            'time' => 0
        ]);

        // TODO:: write some assertion to check queue
    }
}
