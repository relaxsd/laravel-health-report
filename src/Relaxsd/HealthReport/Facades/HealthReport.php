<?php namespace Relaxsd\HealthReport\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array send(array $report)
 *
 * @see \Relaxsd\HealthReport\HealthReportClient
 */
class HealthReport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'healthreport';
    }
}
