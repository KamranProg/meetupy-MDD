<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\MeetupGroup;
use Illuminate\Support\Str;



class EvenController extends Controller
{
    public function index(): Response
    {
        $events = Event::query()
        ->with('meetupGroup.user')
        ->where('starts_at', '>=', now())
        ->orderBy('starts_at', 'asc')
        ->get();

        return Inertia::render('events/index', [
            'events' => $events
        ]);

    }

    public function show(Event $event): Response
    {
        $event->load('meetupGroup.user');

        return Inertia::render('events/show', [
            'event' => $event
        ]);
    }

    public function create(): Response
    {
        $meetupGroups = MeetupGroup::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('events/create', [
            'meetupGroups' => $meetupGroups
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']);

        $event = Event::create($validated);

        return redirect()->route('events.show', $event);
    }

    public function edit(Event $event): Response
    {
        return Inertia::render('events/edit', ['event' => $event]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();
        $event->update($validated);

        return redirect()->route('events.show', $event);
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('events.index');
    }
}
