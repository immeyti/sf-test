<?php

namespace App\Services;

use App\Exceptions\AgentHasDelayReportInProcessingException;
use App\Exceptions\OrderDelayReportIsProcessingException;
use App\Models\Order;
use App\Models\User;
use App\Services\QueueService\DelayReportQueueService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DelayReportService
{
    public function __construct(
        private readonly DelayReportQueueService $delayReportQueueService,
        private readonly OrderService $orderService,
        private readonly AgentService $agentService
    ) {}

    public function assignToAgent(User $agent): void
    {
        if ($this->agentService->agentHasAnyDelayReportInProcessing($agent)) {
            throw new AgentHasDelayReportInProcessingException('', ResponseAlias::HTTP_BAD_REQUEST);
        }

        $order = $this->delayReportQueueService->getFirst();
        /** @var Order $order */
        $orderModel = $this->orderService->getById($order->id);

        if ($this->orderService->orderHasAnyDelayReportInProcessing($orderModel)) {
            throw new OrderDelayReportIsProcessingException('', ResponseAlias::HTTP_BAD_REQUEST);
        }

        $agent->delayReports()->attach($orderModel->delayReports()->first());
        // TODO:: we should have a method to dequeue by index or value
        $this->delayReportQueueService->dequeue();
    }
}
