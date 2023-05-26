<?php

namespace Tests\Feature;

use App\enums\DelayReportStatusEnum;
use App\Models\DelayReport;
use App\Models\Order;
use App\Models\Trip;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AssignDelayReportToAgentTest extends TestCase
{
    use DatabaseMigrations;

    public function test_agent_can_take_a_delay_report()
    {
        // prepare data
        $client = $this->createClient();
        $agent = $this->createAgent();
        $order = Order::factory()
            ->for($client, 'client')
            ->has(Trip::factory()->notValidToNewEstimate())
            ->create();

        (new OrderService())->delay($order);

        // action
        $response = $this->actingAs($agent)
            ->post('/api/assign-delay-report');


        $response->assertStatus(200);

        $this->assertDatabaseHas('delay_reports_agents', [
            'agent_id' => $agent->id,
            'delay_report_id' => $delayReport->id,
            'status' => DelayReportStatusEnum::PROCESSING,
        ]);
    }
}
