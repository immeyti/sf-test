<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    /**
     * @return mixed
     */
    protected function createClient(): mixed
    {
        return User::factory()
            ->client()
            ->create();
    }

    /**
     * @return mixed
     */
    protected function createAgent(): mixed
    {
        return User::factory()
            ->agent()
            ->create();
    }
}
