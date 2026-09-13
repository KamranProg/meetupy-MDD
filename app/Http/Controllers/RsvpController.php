<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RsvpController extends Controller
{
    public function store(Event $event): RedirectResponse
    {
        $event->users()->syncWithoutDetaching([Auth::id()]);

        return back();
    }


    public function destroy(Event $event): RedirectResponse
    {
        $event->users()->detach(Auth::id());

        return back();
    }
}
