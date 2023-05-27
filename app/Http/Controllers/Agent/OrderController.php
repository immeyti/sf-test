<?php

namespace App\Http\Controllers\Agent;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Services\DelayReportService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    public function __construct(
        protected readonly DelayReportService $delayReportService
    ) {}

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = request()->user();

            $this->delayReportService->assignToAgent($user);

            return $this->returnSuccess(ResponseAlias::HTTP_NO_CONTENT);
        } catch (CustomException $e) {
            return $this->returnError($e->getCode(), [
                'message' => $e->getMessage()
            ]);
        }
    }
}
