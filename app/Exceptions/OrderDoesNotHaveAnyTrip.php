<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderDoesNotHaveAnyTrip extends CustomException
{
    protected $code = ResponseAlias::HTTP_UNPROCESSABLE_ENTITY;
}
