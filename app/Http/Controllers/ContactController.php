<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\ContactStoreRequest;
use App\Mail\ContactMail;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(ContactStoreRequest $request): RedirectResponse
    {
        try {
            Mail::to("mamun167359@gmail.com")->send(new ContactMail($request->validated()));

            $flash = [
                "message" => 'Message sent successfully.'
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Message sent failed!",
            ];

            Log::error("Message sent failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('home')
            ->with('flash', $flash);
    }
}
