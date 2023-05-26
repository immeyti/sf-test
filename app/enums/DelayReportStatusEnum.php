<?php

namespace App\enums;

enum DelayReportStatusEnum: string
{
    case PROCESSING = 'processing';
    case DONE = 'done';
}
