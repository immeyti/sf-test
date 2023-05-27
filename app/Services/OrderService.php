<?php

namespace App\Services;

use App\enums\DelayReportStatusEnum;
use App\enums\TripStatusEnum;
use App\Events\OrderDelayed;
use App\Exceptions\FailedToGetDeliveryEstimate;
use App\Exceptions\OrderDeliveryTimeIsNotEnded;
use App\Models\DelayReport;
use App\Models\Order;
use App\Services\EstimatorService\DeliveryEstimatorServiceInterface;
use App\Services\QueueService\DelayReportQueueService;
use Illuminate\Database\Eloquent\Builder;

class OrderService
{

    public function __construct(
        private readonly DeliveryEstimatorServiceInterface $deliveryEstimator,
        private readonly DelayReportQueueService $delayReportQueueService
    ) {}

    /**
     * @param Order $order
     * @return Order
     * @throws OrderDeliveryTimeIsNotEnded
     */
    public function delayReport(Order $order): Order
    {
        if (!$this->isValidToDelayReportRequest($order)) {
            throw new OrderDeliveryTimeIsNotEnded();
        }

        try {
            if ($this->orderHasActiveTrip($order)){
                $newEstimate = $this->deliveryEstimator->getEstimate($order);

                $order->increment('delivery_time', $newEstimate);
            } else {

                if (! $this->orderHasAnyDelayReportInProcessing($order)) {
                    $this->delayReportQueueService->enqueue($order);
                }
                $newEstimate = 0;
            }

            OrderDelayed::dispatch($order, $newEstimate);
            return $order;
        } catch (FailedToGetDeliveryEstimate $e) {
            // TODO:: handle ...
        }
    }

    /**
     * @param Order $order
     * @param int $delayTime
     * @return Order
     */
    public function addDelayReport(Order $order, int $delayTime): Order
    {
        $order->delayReports()->create([
            'time' => $delayTime
        ]);

        return $order;
    }


    /**
     * @param Order $order
     * @return bool
     */
    public function isValidToDelayReportRequest(Order $order): bool
    {
        return $order->expected_delivery_time < now();
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function orderHasActiveTrip(Order $order): bool
    {
        return in_array($order->trip?->status, TripStatusEnum::getValidStatusListToNewEstimate());
    }

    public function getById(int $id)
    {
        return Order::find($id);
    }

    public function orderHasAnyDelayReportInProcessing(Order $order): bool
    {
        return $order->delayReports()->whereHas('agents', function (Builder $query) {
            $query->where('status', DelayReportStatusEnum::PROCESSING);
        })->exists();
    }
}
