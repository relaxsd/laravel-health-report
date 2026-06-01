<?php namespace Relaxsd\HealthReport;

class HealthReportClient
{
    /** @var string */
    protected $url;

    /** @var string */
    protected $token;

    /** @var int */
    protected $timeout;

    public function __construct($url, $token, $timeout = 30)
    {
        $this->url = $url;
        $this->token = $token;
        $this->timeout = (int) $timeout;
    }

    /**
     * Send an associative array as a JSON health report.
     *
     * @param  array  $report
     * @return array  Decoded JSON response from the monitor
     * @throws \RuntimeException
     */
    public function send(array $report)
    {
        if (empty($this->url)) {
            throw new \RuntimeException('Health report URL is not configured (HEALTH_MONITOR_URL).');
        }

        if (empty($this->token)) {
            throw new \RuntimeException('Health report token is not configured (HEALTH_MONITOR_TOKEN).');
        }

        if (!function_exists('curl_init')) {
            throw new \RuntimeException('cURL extension is required to send health reports.');
        }

        $payload = json_encode($report);
        if ($payload === false) {
            throw new \RuntimeException('Failed to encode health report as JSON.');
        }

        $ch = curl_init($this->url);

        curl_setopt_array($ch, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
                'Accept: application/json',
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $this->timeout,
        ));

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($body === false) {
            throw new \RuntimeException('Health report request failed: ' . $error);
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \RuntimeException(
                'Health report request failed with HTTP ' . $httpCode . ': ' . $body
            );
        }

        $decoded = json_decode($body, true);

        return is_array($decoded) ? $decoded : array('raw' => $body);
    }
}
