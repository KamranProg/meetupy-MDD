# Meetupy

A modern event management platform for creating and managing meetup groups and events with real-time RSVP functionality and email notifications.

## Overview

Meetupy is a full-stack web application built with **Laravel 13** and **React 19** (via Inertia.js) that empowers communities to organize and participate in local events. Users can create meetup groups, organize events, RSVP to gatherings, and receive email notifications—all within an intuitive, modern interface.

## Key Features

- 🏘️ **Meetup Groups** — Create and manage communities around shared interests
- 📅 **Event Management** — Organize events with dates, locations, descriptions, and attendee tracking
- ✋ **RSVP System** — Users can register for events and see other attendees
- 📧 **Email Notifications** — Automated confirmations sent to users when they RSVP
- 👤 **User Profiles** — Secure authentication with customizable profiles and settings
- 🎨 **Modern UI** — Built with React, Tailwind CSS, and Radix UI components
- 🔒 **Role-Based Access** — Authorization policies ensure users can only modify their own events and groups

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Client (Browser)                         │
│  React 19 + Inertia.js + Tailwind CSS + Radix UI           │
│                                                              │
│  Pages: Dashboard, Events, Groups, Settings                │
└─────────────────────────────────────────────────────────────┘
                            ↓
                     HTTP/Inertia Requests
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  Laravel 13 Server                          │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │             HTTP Controllers                         │  │
│  │  EventController, RsvpController, etc.              │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓                                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              Eloquent Models & Policies              │  │
│  │  Event, MeetupGroup, User (with authorization)      │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓                                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │           Background Jobs & Mailing                 │  │
│  │  SendRsvpEmail (queued jobs for notifications)      │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│              SQLite Database                                │
│                                                              │
│  Tables:                                                    │
│  • users (authentication & profiles)                        │
│  • meetup_groups (user-owned groups)                        │
│  • events (group events with scheduling)                    │
│  • event_user (RSVP pivot table)                            │
│                                                              │
│  Related: migrations, seeders, factories                    │
└─────────────────────────────────────────────────────────────┘
```

## Database Schema

### Core Models

**User** — Authentication & profiles
- Manages authentication via Fortify
- Owns meetup groups and RSVPs to events

**MeetupGroup** — Community spaces
- `user_id` — Owner/creator
- `name` — Group name
- `description` — What the group is about
- `location` — Where the group meets

**Event** — Individual gatherings
- `meetup_group_id` — Parent group
- `title` — Event name
- `slug` — URL-friendly identifier
- `description` — Event details
- `location` — Where it takes place
- `starts_at` — When it happens
- Pivot: `event_user` (RSVPs with timestamps)

## Installation & Setup

### Prerequisites

- PHP 8.3+
- Node.js 18+
- SQLite (included) or a compatible database
- Composer
- npm

### Quick Start

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd meetupy
   ```

2. **Run setup**
   ```bash
   composer run setup
   ```
   This command will:
   - Install PHP dependencies
   - Generate the application key
   - Create and migrate the database
   - Install npm packages
   - Build the frontend

3. **Start the development server**
   ```bash
   composer run dev
   ```
   This starts both the Laravel and Vite dev servers concurrently.

4. **Visit the application**
   Open your browser to `http://localhost` (or the port indicated in terminal)

### Alternative: Manual Setup

```bash
# PHP setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Frontend setup
npm install
npm run dev

# In another terminal, start the Laravel server
php artisan serve
```

## Available Commands

```bash
# Development
composer run dev          # Start dev server (Laravel + Vite)

# Testing & Code Quality
composer test             # Run the full test suite
composer run lint         # Format code with Pint
composer run lint:check   # Check code style without fixing
composer run types:check  # Run static analysis with PHPStan
composer run ci:check     # Full CI check (tests, types, linting)

# Frontend
npm run build             # Build frontend for production
npm run dev               # Start Vite dev server (standalone)
npm run check             # Check TypeScript & linting
npm run check:fix         # Auto-fix TypeScript & linting issues

# Database
php artisan migrate       # Run pending migrations
php artisan seed          # Seed the database
php artisan tinker        # Interactive shell

# Artisan Utilities
php artisan route:list    # View all registered routes
php artisan config:show   # View configuration values
```

## Project Structure

```
meetupy/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Route controllers (EventController, RsvpController)
│   │   ├── Requests/           # Form validation (StoreEventRequest, etc.)
│   │   └── Middleware/         # Inertia & appearance middleware
│   ├── Models/                 # Eloquent models (Event, MeetupGroup, User)
│   ├── Policies/               # Authorization (EventPolicy)
│   ├── Jobs/                   # Background jobs (SendRsvpEmail)
│   ├── Mail/                   # Mailables (RsvpConfirmation)
│   └── Actions/                # Fortify actions (CreateNewUser, etc.)
│
├── resources/
│   ├── js/
│   │   └── pages/              # Inertia page components (React)
│   └── views/                  # Blade templates (minimal; mostly Inertia)
│
├── database/
│   ├── migrations/             # Database schema
│   ├── seeders/                # Database seeders
│   └── factories/              # Model factories for testing
│
├── routes/
│   ├── web.php                 # Web routes (frontend)
│   └── api.php                 # API routes (if applicable)
│
├── tests/                       # Pest test suites
│
├── .env.example                 # Environment template
├── composer.json                # PHP dependencies
├── package.json                 # JavaScript dependencies
└── vite.config.js               # Vite configuration
```

## Authentication & Authorization

The application uses **Laravel Fortify** for authentication with email verification support.

- **Registration** — Users sign up with email and password
- **Login** — Secure session-based authentication
- **Policies** — Users can only edit/delete their own events and groups
- **Profile Settings** — Users can update profile information, password, and appearance preferences

## Testing

Run the full test suite:
```bash
php artisan test
```

Run specific tests:
```bash
php artisan test --filter=EventTest
vendor/bin/pest tests/Feature/Events/EventTest.php
```

The project uses **Pest** for testing with feature and unit test suites.

## Development Workflow

1. **Start the dev server** — `composer run dev`
2. **Make changes** to PHP/React code
3. **Verify changes** in the browser (hot-reload enabled for both)
4. **Run tests** — `php artisan test`
5. **Format code** — `composer run lint`
6. **Commit changes** following conventional commits

## Performance & Scalability

- **Queued Jobs** — RSVP emails are sent asynchronously via queued jobs
- **Database Indexing** — Eloquent relationships optimized for common queries
- **Frontend Optimization** — React with Inertia provides fast, server-rendered pages
- **Type Safety** — TypeScript on frontend, PHPStan static analysis on backend

## Technology Stack

**Backend**
- Laravel 13 — Web framework
- Laravel Fortify 1.37 — Authentication
- Pest 5.1 — Testing framework
- Laravel Pint 1.27 — Code formatter
- PHPStan — Static analysis

**Frontend**
- React 19 — UI library
- Inertia.js 3.0 — Server-side rendering bridge
- TypeScript 5.7 — Type safety
- Tailwind CSS 4.0 — Utility-first styling
- Radix UI — Accessible component primitives
- Vite 8 — Build tool & dev server

**Database**
- SQLite (development/testing)
- Supports MySQL, PostgreSQL, etc. (production)

## License

MIT License — see LICENSE file for details.

## Support

For questions or issues, please open an issue on the project repository.
