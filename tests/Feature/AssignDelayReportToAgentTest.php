<?php

namespace Tests\Feature;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\Trip;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AssignDelayReportToAgentTest extends TestCase
{
    use DatabaseMigrations;

    public function test_agent_can_take_a_delay_report()
    {
        $client = $this->createClient();
        $agent = $this->createAgent();

        $order = Order::factory()
            ->for($client, 'client')
            ->has(Trip::factory()->validToNewEstimate())
            ->create();
        $delayReport = DelayReport::factory()
            ->for($order)
            ->create();

        $response = $this->actingAs($agent)
            ->post('/api/assign-delay-report');


    }
}
