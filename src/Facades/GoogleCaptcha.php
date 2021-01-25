<?php

namespace mithatguner\googlecaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class GoogleCaptcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'googlecaptcha';
    }
}
