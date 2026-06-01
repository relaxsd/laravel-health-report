<?php namespace Relaxsd\HealthReport;

use Illuminate\Support\ServiceProvider;

class HealthReportServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->package('relaxsd/laravel-health-report');
    }

    public function register()
    {
        $this->app['healthreport'] = $this->app->share(function ($app) {
            $config = $app['config']->get('laravel-health-report::config');

            return new HealthReportClient(
                isset($config['url']) ? $config['url'] : '',
                isset($config['token']) ? $config['token'] : '',
                isset($config['timeout']) ? $config['timeout'] : 30
            );
        });
    }

    public function provides()
    {
        return array('healthreport');
    }
}
