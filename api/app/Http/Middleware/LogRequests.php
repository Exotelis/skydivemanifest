<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class LogRequests
 * @package App\Http\Middleware
 */
class LogRequests
{
    /**
     * Handle request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle ($request, \Closure $next)
    {
        return $next($request);
    }

    /**
     * Do some work after response has been sent to the user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    public function terminate ($request, $response)
    {
        $this->log($request, $response);
    }

    /**
     * Write to log.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    protected function log ($request, $response)
    {
        $user = Auth::user() ?
            $request->user()->id.'|'.$request->user()->username.'|'.$request->user()->email :
            'anonymous';
        $ips = implode(', ', $request->getClientIps());
        $status = $response->getStatusCode();
        $method = $request->getMethod();
        $url = $request->fullUrl();
        $agent = $request->userAgent();

        if ($status >= 300) {
            $content = json_decode($response->getContent(), true);
            $status = $status . ' - ' . $content['message'] ?? $status;
        }

        $log = "[Request] [{$status}] {$method}@{$url}: '{$user}'({$ips}) - Agent: {$agent})";

        Log::info($log);
    }
}
