<?php

namespace App\Health;

use Illuminate\Support\Facades\Facade;

class HealthCheckFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'health.check';
    }
}