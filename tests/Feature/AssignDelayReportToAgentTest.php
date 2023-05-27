<?php

namespace Tests\Feature;

use App\enums\DelayReportStatusEnum;
use App\Models\DelayReport;
use App\Models\Order;
use App\Models\Trip;
use App\Services\OrderService;
use App\Services\QueueService\DelayReportQueueService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
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
        $delayReport = DelayReport::factory()
            ->for($order)
            ->create();

        $mock = $this->mockDelayReportQueueDequeueMethod(json_decode(json_encode($order)));
        $mock->shouldReceive('getFirst')->once()->andReturn(json_decode(json_encode($order)));

        // action
        $response = $this->actingAs($agent)
            ->post('/api/admin/assign-delay-report');

        // assertion
        $response->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
        $this->assertDatabaseHas('delay_reports_agents', [
            'agent_id' => $agent->id,
            'delay_report_id' => $delayReport->id,
            'status' => DelayReportStatusEnum::PROCESSING,
        ]);
    }

    public function test_agent_cannot_take_a_delay_report_that_is_processing_by_so_else()
    {
        // prepare data
        $client = $this->createClient();
        $agent = $this->createAgent();
        $anotherAgent = $this->createAgent();
        $order = Order::factory()
            ->for($client, 'client')
            ->has(Trip::factory()->notValidToNewEstimate())
            ->create();
        $delayReport = DelayReport::factory()
            ->for($order)
            ->create();
        $agent->delayReports()->attach($delayReport);

        // mock queue
        $mock = $this->mockDelayReportQueueGetFirstMethod(json_decode(json_encode($order)));
        $mock->shouldNotReceive('dequeue');

        // action
        $response = $this->actingAs($anotherAgent)
            ->post('/api/admin/assign-delay-report');

        // assertion
        $response->assertStatus(ResponseAlias::HTTP_BAD_REQUEST);

        $this->assertDatabaseHas('delay_reports_agents', [
            'agent_id' => $agent->id,
            'delay_report_id' => $delayReport->id,
            'status' => DelayReportStatusEnum::PROCESSING,
        ]);
        $this->assertDatabaseCount('delay_reports_agents', 1);
    }

    public function test_agent_cannot_have_multiple_delay_request_in_processing()
    {
        // prepare data
        $client = $this->createClient();
        $agent = $this->createAgent();
        $order = Order::factory()
            ->for($client, 'client')
            ->has(Trip::factory()->notValidToNewEstimate())
            ->create();
        $delayReport = DelayReport::factory()
            ->for($order)
            ->create();
        $agent->delayReports()->attach($delayReport);

        $secondOrder = Order::factory()
            ->for($client, 'client')
            ->has(Trip::factory()->notValidToNewEstimate())
            ->create();
        $secondDelayReport = DelayReport::factory()
            ->for($secondOrder)
            ->create();

        $this->mock(DelayReportQueueService::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('getFirst');
            $mock->shouldNotReceive('dequeue');
        });

        // action
        $response = $this->actingAs($agent)
            ->post('/api/admin/assign-delay-report');

        $response->assertStatus(ResponseAlias::HTTP_BAD_REQUEST);


        $this->assertDatabaseHas('delay_reports_agents', [
            'agent_id' => $agent->id,
            'delay_report_id' => $delayReport->id,
            'status' => DelayReportStatusEnum::PROCESSING,
        ]);
        $this->assertDatabaseCount('delay_reports_agents', 1);

    }
}
