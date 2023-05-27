<?php

namespace Tests\Feature;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class VendorsDelayReportsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_vendor_reports()
    {
        $this->travel(-14)->days();
        $vendorOutOfScope = Vendor::factory()
            ->has(
                Order::factory()->has(
                    DelayReport::factory()
                        ->state(function (array $attributes, Order $order) {
                            return ['time' => 300];
                        })
                )
            )->create();

        $this->travel(10)->days();

        $firstVendor = $this->prepareData(30, 40); // 30 + 40 + 15 = 85
        $secondVendor = $this->prepareData(5, 100);  // 5 + 100 + 15 = 120
        $thirdVendor = $this->prepareData(5, 10);  // 5 + 10 + 15 = 30

        $this->get('/api/admin/vendor-delay-report')
            ->assertStatus(200)
            ->assertJson([
               'data' => [
                   [
                       'id' => $secondVendor->id,
                       'total_delay' => 120
                   ],
                   [
                       'id' => $firstVendor->id,
                       'total_delay' => 85
                   ],
                   [
                       'id' => $thirdVendor->id,
                       'total_delay' => 30
                   ],
               ]
            ]);
    }

    /**
     * @return array
     */
    public function prepareData($firstTime, $secondTime): Vendor
    {
        /** @var Vendor $vendor */
        $vendor = Vendor::factory()
            ->has(
                Order::factory()->has(
                    DelayReport::factory()
                        ->state(function (array $attributes, Order $order) use ($firstTime) {
                            return ['time' => $firstTime];
                        })
                )
            )->create();
        $order = Order::factory()
            ->for($vendor)
            ->has(DelayReport::factory()) // time will be 15
            ->create();
        DelayReport::factory()
            ->for($order)
            ->create(['time' => $secondTime]);

        return $vendor;
    }
}
