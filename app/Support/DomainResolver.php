<?php

namespace App\Support;

use Illuminate\Http\Request;

class DomainResolver
{
    public static function normalize(?string $host): string
    {
        $host = strtolower(trim((string) $host));
        $host = preg_replace('/:\d+$/', '', $host);
        $host = rtrim($host, '.');
        if (strpos($host, 'www.') === 0) {
            $host = substr($host, 4);
        }
        return $host;
    }

    public static function host(?string $host = null): string
    {
        if ($host === null) {
            if (app()->bound('request')) {
                $host = request()->getHost();
            } else {
                $host = '';
            }
        }
        return self::normalize($host);
    }

    public static function primaryDomain(): string
    {
        return self::normalize((string) config('saas.primary_domain', ''));
    }

    public static function hasPrimaryDomain(): bool
    {
        return self::primaryDomain() !== '';
    }

    public static function isSaasDomain(?string $host = null): bool
    {
        $normalized = self::host($host);
        $primary = self::primaryDomain();
        if ($primary !== '' && $normalized === $primary) {
            return true;
        }
        return $normalized === 'platform.localhost';
    }

    public static function isLocalHost(?string $host = null): bool
    {
        $normalized = self::host($host);
        if ($normalized === '') {
            return false;
        }

        $locals = self::configuredLocalHosts();
        if (in_array($normalized, $locals, true)) {
            return true;
        }

        if (substr($normalized, -10) === '.localhost') {
            return true;
        }

        if ($normalized === 'herbalsglow.test' || substr($normalized, -16) === '.herbalsglow.test') {
            return true;
        }

        if (substr($normalized, -5) === '.test') {
            return true;
        }

        return false;
    }

    /**
     * Super admin + /platform are allowed on the SaaS host and on local dev.
     * If no primary domain is configured, keep current “any host” behaviour.
     */
    public static function allowsSaasSuite(?string $host = null): bool
    {
        if (!self::hasPrimaryDomain()) {
            return true;
        }
        return self::isSaasDomain($host) || self::isLocalHost($host);
    }

    /**
     * First-store fallback for XAMPP / localhost only — not for unknown public hosts.
     */
    public static function allowsDevStoreFallback(?string $host = null): bool
    {
        return self::isLocalHost($host);
    }

    public static function isMerchantDomain(?string $host = null): bool
    {
        return !self::isSaasDomain($host);
    }

    public static function fromRequest(?Request $request = null): string
    {
        $request = $request ?: request();
        return self::normalize($request ? $request->getHost() : '');
    }

    private static function configuredLocalHosts(): array
    {
        $raw = (string) config('saas.local_hosts', 'localhost,127.0.0.1,::1');
        $hosts = array_filter(array_map(function ($item) {
            return self::normalize($item);
        }, explode(',', $raw)));

        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        if ($appHost) {
            $hosts[] = self::normalize($appHost);
        }

        return array_values(array_unique($hosts));
    }
}
