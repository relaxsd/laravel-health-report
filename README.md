# Laravel Health Report (4.2)

Standalone Laravel 4.2 package for sending arbitrary health status reports to [My Health Monitor](https://github.com/relaxsd/my-health-monitor). Published as [`relaxsd/laravel-health-report`](https://github.com/relaxsd/laravel-health-report).

Use it from scheduled Artisan commands, event listeners, or anywhere you need to push server-side status to the monitor's `/api/agent-reports` endpoint.

## Installation

This package is not on Packagist. Add the GitHub repository to your Laravel app's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/relaxsd/laravel-health-report"
        }
    ],
    "require": {
        "relaxsd/laravel-health-report": "^0.1.0"
    }
}
```

Then run `composer update relaxsd/laravel-health-report`.

Composer resolves versions from Git tags on the repository (e.g. `0.1.0`).

## Configuration

### 1. Register the service provider

In `app/config/app.php`:

```php
'providers' => array(
    // ...
    'Relaxsd\HealthReport\HealthReportServiceProvider',
),
```

### 2. Register the facade (optional)

In `app/config/app.php`:

```php
'aliases' => array(
    // ...
    'HealthReport' => 'Relaxsd\HealthReport\Facades\HealthReport',
),
```

### 3. Environment variables

Add to your `.env` file:

```env
HEALTH_MONITOR_URL=https://your-health-monitor.example.com/api/agent-reports
HEALTH_MONITOR_TOKEN=your-client-token-from-agent-clients-yaml
```

These are read by default via `getenv()` in the package config. You can publish and override the config:

```bash
php artisan config:publish relaxsd/laravel-health-report
```

Published config lives at `app/config/packages/relaxsd/laravel-health-report/config.php`.

## Usage

Send any associative array as a JSON report:

```php
use HealthReport;

HealthReport::send(array(
    'status' => 'ok',
    'queue_size' => Queue::size(),
    'failed_jobs' => DB::table('failed_jobs')->count(),
));
```

From a scheduled Artisan command:

```php
<?php

use Illuminate\Console\Command;
use HealthReport;

class SendHealthReport extends Command
{
    protected $name = 'health:report';
    protected $description = 'Send application health status to My Health Monitor';

    public function fire()
    {
        $report = array(
            'status' => 'ok',
            'app_env' => App::environment(),
        );

        try {
            $response = HealthReport::send($report);
            $this->info('Report sent: ' . json_encode($response));
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
        }
    }
}
```

Schedule it in `app/start/artisan.php` or your scheduler:

```php
$schedule->command('health:report')->everyMinute();
```

## API

The monitor expects:

- **Method:** `POST`
- **URL:** `/api/agent-reports` (full URL in `HEALTH_MONITOR_URL`)
- **Auth:** `Authorization: Bearer <token>`
- **Body:** Any JSON object

See [docs/agent.md](../../docs/agent.md) in the main repository for monitor setup and report structure examples.
