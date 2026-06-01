<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Health Monitor URL
    |--------------------------------------------------------------------------
    |
    | Full URL of the agent-reports endpoint, e.g.
    | https://your-health-monitor.example.com/api/agent-reports
    |
    */

    'url' => getenv('HEALTH_MONITOR_URL') ?: 'https://your-health-monitor.example.com/api/agent-reports',

    /*
    |--------------------------------------------------------------------------
    | Health Monitor Token
    |--------------------------------------------------------------------------
    |
    | Bearer token from agent-clients.yaml (client_token for this server).
    |
    */

    'token' => getenv('HEALTH_MONITOR_TOKEN') ?: '',

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time in seconds to wait for the monitor to respond.
    |
    */

    'timeout' => 30,

);
