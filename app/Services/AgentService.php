<?php

namespace App\Services;

use App\enums\DelayReportStatusEnum;
use App\Models\DelayReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AgentService
{
    public function agentHasAnyDelayReportInProcessing(User $agent): bool
    {
        return $agent->delayReports()
            ->where('status', DelayReportStatusEnum::PROCESSING)
            ->exists();
    }
}
