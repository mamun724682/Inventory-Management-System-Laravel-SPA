<?php

namespace App\Http\Controllers;

use App\Enums\Setting\SettingFieldsEnum;
use App\Helpers\BaseHelper;
use App\Http\Requests\Profile\ProfileUpdateImageRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\User;
use App\Services\FileManagerService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'settings' => [
                "fields" => BaseHelper::convertKeyValueToLabelValueArray(SettingFieldsEnum::choices()),
                "data" => settings()->all()
            ],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function updateImage(ProfileUpdateImageRequest $request): RedirectResponse
    {
        /** @var FileManagerService $fileManagerService */
        $fileManagerService = app(FileManagerService::class);

        try {
            $user = $request->user();
            $oldPhoto = $user->getRawOriginal("photo");
            $photo = $fileManagerService->uploadFile(
                file: $request->photo,
                uploadPath: User::PHOTO_PATH,
                deleteFileName: $oldPhoto
            );

            $user->photo = $photo;
            $user->save();

            $flash = [
                "message" => 'Profile image uploaded successfully.'
            ];
        } catch (\Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Profile image upload failed!",
            ];

            Log::error("Profile image upload failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with('flash', $flash);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
