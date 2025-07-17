<?php

namespace Botble\DHL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $signature = $request->header('DHL-Signature');

        if (! $signature && setting('shipping_dhl_webhook_secret')) {
            if (setting('shipping_dhl_logging')) {
                Log::channel('dhl')->error('Webhook signature missing');
            }

            return response()->json(['error' => 'Webhook signature missing'], 403);
        }

        return $next($request);
    }
} 