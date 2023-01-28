<?php

declare(strict_types=1);

namespace BBSLab\CloudinaryField\Http\Controllers;

use BBSLab\CloudinaryField\NovaCloudinary;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class ToolController extends Controller
{
    public function __invoke(NovaRequest $request): Response
    {
        /** @var ?\BBSLab\CloudinaryField\NovaCloudinary $tool */
        $tool = collect(Nova::registeredTools())->first(fn(Tool $tool) => $tool instanceof NovaCloudinary);

        return Inertia::render('NovaCloudinary', [
            'configuration' => $tool?->configuration(),
        ]);
    }
}
