<?php


namespace atefe93\TowFactorAuthTest;


use atefe93\TowFactorAuth\TowFactorAuthServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [TowFactorAuthServiceProvider::class];
    }
}
