<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class EvenController extends Controller
{
    public function index(Request $request): Response
    {
        $events = Event::query()
        ->with('meetupGroup.user')
        ->where('starts_at', '>=', now())
        ->orderBy('starts_at', 'asc')
        ->get();

        return Inertia::render('events/index', [
            'events' => $events,
            'can' => [
                'create' => $request->user()?->can('create', Event::class) ?? false
            ]
        ]);

    }

    public function show(Request $request, Event $event): Response
    {
        $event->load('meetupGroup.user');

        return Inertia::render('events/show', [
            'event' => $event,
            'can' => [
                'update' => $request->user()?->can('update', $event) ?? false,
                'delete' => $request->user()?->can('delete', $event) ?? false,
            ]
        ]);
    }

    public function create(): Response
    {
        $meetupGroups = Auth::user()->meetupGroups()
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
        Gate::authorize('update', $event);
        return Inertia::render('events/edit', ['event' => $event]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);
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
