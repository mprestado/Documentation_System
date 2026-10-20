# Client Documentation System

A Laravel web application for organizing client records, services, document attachments, requirements checklists, and processing status.

**Stack:** PHP 8.2+, Laravel 12, Blade, Tailwind CSS, Alpine.js, Vite.

## What is included

- Client records with search, service filtering, and status updates
- Service records and requirements checklists
- Document uploads, downloads, and attachment records
- Tracking fields for agencies, documents, and notes
- Dashboard, account authentication, and profile screens

## Repository layout

The application is nested inside [`dsb-documentation-system/`](dsb-documentation-system). Run Composer, npm, and Artisan commands from that directory.

| Location | Contents |
| --- | --- |
| [app/Http/Controllers](dsb-documentation-system/app/Http/Controllers) | Application request handling |
| [app/Models](dsb-documentation-system/app/Models) | Client, service, user, and document models |
| [resources/views](dsb-documentation-system/resources/views) | Blade interface templates |
| [routes/web.php](dsb-documentation-system/routes/web.php) | Web routes |
| [database/migrations](dsb-documentation-system/database/migrations) | Database schema changes |
| [tests](dsb-documentation-system/tests) | Existing test sources |

## Local setup

Requirements: PHP 8.2+, Composer, Node.js/npm compatible with the locked frontend dependencies, and a local database.

```bash
git clone https://github.com/mprestado/Documentation_System.git
cd Documentation_System/dsb-documentation-system
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

On Windows Command Prompt, use `copy .env.example .env` for the environment-file step.

Configure the database connection in your local `.env`, create the database, then run:

```bash
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

Open http://127.0.0.1:8000. Use sample client data when evaluating the application.

## Development and validation

The application defines `composer dev` for the development processes and `composer test` for its existing PHP tests. Frontend assets are built with `npm run build`.

These are repository-provided commands; this overview does not certify a successful installation, test run, or production deployment.

## Project scope

This repository is an application snapshot. Features and routes should be reviewed in the source before deployment. Google sign-in routes exist, but their referenced `GoogleController` is absent from this snapshot, so Google sign-in needs implementation or repair.

[More projects](https://github.com/mprestado/mprestado/blob/main/PROJECTS.md)
