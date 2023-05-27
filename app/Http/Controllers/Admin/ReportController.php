<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorDelayReport as VendorDelayReportResource;
use App\Services\VendorService;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct(
        protected readonly VendorService $vendorService
    ) {}

    public function __invoke()
    {
        $startOfWeek = Carbon::now()->subDays(7)->startOfDay();
        $endOfWeek = Carbon::now();

        $report = $this->vendorService->delayReportSummery($startOfWeek, $endOfWeek);

        return $this->returnSuccess(200, VendorDelayReportResource::make($report));
    }
}
