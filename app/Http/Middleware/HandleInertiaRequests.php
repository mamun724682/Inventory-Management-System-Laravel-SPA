<?php

namespace App\Http\Middleware;

use App\Enums\Setting\SettingFieldsEnum;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'isSuccess' => fn () => $request->session()->get('flash')['isSuccess'] ?? true,
                'message' => fn () => $request->session()->get('flash')['message'] ?? null,
            ],
            'currency' => settings()->get(SettingFieldsEnum::CURRENCY_SYMBOL->value, '$'),
            'decimal_point' => settings()->get(SettingFieldsEnum::DECIMAL_POINT->value, 4),
        ];
    }
}
