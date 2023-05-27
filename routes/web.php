<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {


// Define our queue name
    $queueName = 'delay-reports';

// Put some items in our queue
// You can run this code outside your long-running worker
//    Redis::rpush($queueName, 'item1');
//    Redis::rpush($queueName, json_encode(['order' => 'rer']));

    dd(Redis::LRANGE($queueName, 0, -1));
});
