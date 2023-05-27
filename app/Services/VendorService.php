<?php

namespace App\Services;

use App\Models\Vendor;
use Carbon\Carbon;

class VendorService
{
    public function delayReportSummery(\Carbon\Carbon $from, \Carbon\Carbon $to)
    {
        return Vendor::select('vendors.id')
            ->leftJoin('orders', 'vendors.id', '=', 'orders.vendor_id')
            ->leftJoin('delay_reports', 'orders.id', '=', 'delay_reports.order_id')
            ->selectRaw('vendors.id, CAST(COALESCE(SUM(delay_reports.time), 0) AS INT) AS total_delay')
            ->whereBetween('delay_reports.created_at', [$from, $to])
            ->groupBy('vendors.id')
            ->orderBy('total_delay', 'desc')
            ->get();
    }
}
