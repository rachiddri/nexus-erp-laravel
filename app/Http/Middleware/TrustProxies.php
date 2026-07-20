<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * Trusted proxies.
     *
     * Le reverse proxy TLS (scripts/tls-proxy.py :8443 -> app :8000) termine le
     * TLS ; on truste tout (usage local/staging). En prod, restreindre à l'IP du proxy.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    // $headers hérité du parent (X-Forwarded-For/Host/Port/Proto/Prefix/AwsElb).
}
