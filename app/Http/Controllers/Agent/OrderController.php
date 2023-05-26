<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __invoke()
    {
        $user = request()->user();
        
    }
}
