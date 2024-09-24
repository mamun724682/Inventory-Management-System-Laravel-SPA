<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\SettingUpdateRequest;
use App\Services\SettingService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function __construct(private readonly SettingService $service)
    {
    }

    /**
     * @param SettingUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(SettingUpdateRequest $request): RedirectResponse
    {
        try {
            $this->service->update(
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Settings updated successfully.'
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Settings update failed!",
            ];

            Log::error("Settings update failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with('flash', $flash);
    }
}
