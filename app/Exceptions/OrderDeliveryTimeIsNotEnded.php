<?php

namespace App\Exceptions;

use Exception;

class OrderDeliveryTimeIsNotEnded extends CustomException
{
    protected $code = 400;
}
