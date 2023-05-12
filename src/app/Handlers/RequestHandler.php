<?php

namespace app\Handlers;

use app\Interfaces\RequestHandlerInterface;

abstract class RequestHandler implements RequestHandlerInterface
{
    protected string|null $ip = null;
    protected string|null $refererUrl = null;

    public function __construct()
    {
        $this->getRequestIP();
        $this->getPageUrl();
    }

    protected function getPageUrl(): ?string
    {
        if (!is_null($this->refererUrl)) {
            return $this->refererUrl;
        }
        $this->refererUrl = $_SERVER['HTTP_REFERER'] ?? '-';

        return $this->refererUrl;
    }

    protected function getRequestIP()
    {
        if (!is_null($this->ip)) {
            return $this->ip;
        }

        // custom IP in requests for testing purposes
        $customParam = 'customRequestIP';
        if (isset($_GET[$customParam]) && filter_var($_GET[$customParam], FILTER_VALIDATE_IP)) {
            return $this->ip = $_GET[$customParam];
        }

        foreach (['X-Forwarded-For', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $header) {
            if (isset($_SERVER[$header])) {
                if ($extractedIp = $this->extractIpAddr($_SERVER[$header])) {
                    $this->ip = $extractedIp;
                    break;
                }
            }
        }

        return $this->ip;
    }

    private function extractIpAddr(string $ipStr): ?string
    {
        foreach (array_reverse(explode(',', $ipStr)) as $ip) {
            $ip = trim($ip);
            if ($ip = $this->getValidIpAddr($ip)) {
                return $ip;
            }
        }

        return null;
    }

    protected function getValidIpAddr($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
        // to PRODUCTION filter will be:
        // FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    }

    public function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
}
