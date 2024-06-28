<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField\Http\Middleware;

use BBSLab\CloudinaryField\NovaCloudinary;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request):mixed  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);

        return optional($tool)->authorize($request) ? $next($request) : abort(403);
    }

    /**
     * Determine whether this tool belongs to the package.
     */
    public function matchesTool(Tool $tool): bool
    {
        return $tool instanceof NovaCloudinary;
    }
}
