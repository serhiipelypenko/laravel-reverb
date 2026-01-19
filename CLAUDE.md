# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel Vue starter kit with real-time WebSocket functionality via Laravel Reverb. Full-stack application using PHP 8.2+/Laravel 12, Vue 3/TypeScript, and Inertia.js for SPA with SSR support.

## Development Commands

```bash
# Full setup (install deps, create .env, migrate, build)
composer setup

# Start all dev servers concurrently (Laravel, Vite, Queue, Logs)
composer dev

# Start dev with SSR support
composer dev:ssr

# Run tests (clears config cache first)
composer test

# Run a single test file
php artisan test --filter=TestClassName
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Frontend commands
npm run dev           # Vite dev server with HMR
npm run build         # Production build
npm run build:ssr     # Build both CSR and SSR bundles
npm run lint          # ESLint with auto-fix
npm run format        # Prettier formatting
```

## Architecture

### Backend (Laravel)

- **Inertia.js pattern**: Controllers return `Inertia::render()` responses that pass data directly to Vue components as props - no separate JSON API needed
- **Laravel Fortify**: Handles authentication (login, register, password reset, email verification, 2FA). Actions in `app/Actions/Fortify/`, configured in `app/Providers/FortifyServiceProvider.php`
- **Laravel Reverb**: WebSocket server for real-time broadcasting. Events in `app/Events/` implement `ShouldBroadcast`, channel auth in `routes/channels.php`
- **Wayfinder**: Type-safe route generation - routes defined in `resources/js/wayfinder/`

### Frontend (Vue 3 + TypeScript)

- **Entry points**: `resources/js/app.ts` (CSR), `resources/js/ssr.ts` (SSR)
- **Pages**: `resources/js/pages/` - routed page components receiving Inertia props
- **Components**: `resources/js/components/` - reusable UI components (Reka UI based)
- **Composables**: `resources/js/composables/` - Vue 3 composition API hooks
- **Real-time**: Use `useEcho()` from `@laravel/echo-vue` to subscribe to broadcast channels

### Key Data Flows

1. **Page rendering**: HTTP request → Laravel route → Controller → `Inertia::render('PageName', $props)` → Vue component
2. **Broadcasting**: Backend dispatches event → Reverb broadcasts → Frontend `useEcho().listen()` receives update

## Testing

Uses Pest (PHP testing framework). Tests in `tests/Feature/` and `tests/Unit/`. Test database uses SQLite in-memory (`:memory:`).
