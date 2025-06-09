<?php

namespace App\Facades;

use App\Services\QrCodeService;
use Illuminate\Support\Facades\Facade;

class QrCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return QrCodeService::class;
    }
}