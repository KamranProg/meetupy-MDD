import { Head, Link } from '@inertiajs/react';
import EventCard from '@/components/event-card';
import { create, show } from '@/routes/events';
import type { Event } from '@/types';

interface Props {
    events: Event[];
    can: {
        create: boolean;
    };
}

export default function EventsIndex({ events, can }: Props) {
    return (
        <>
            <Head title="Upcoming Events" />

            <div className="flex items-center justify-between">
                <h1 className="text-2xl font-semibold">Upcoming Events</h1>
                {can.create && (
                    <Link href={create.url()} >
                        Create Event
                    </Link>
                )}
            </div>

            <div className="space-y-4">
                {events.map((event) => (
                    <Link key={event.id} href={show(event.slug)}>
                        <EventCard event={event} />
                    </Link>
                ))}
            </div>
        </>
    );
}
