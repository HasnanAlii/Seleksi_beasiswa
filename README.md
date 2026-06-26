# Scholarship Selection Management System

A Laravel-based scholarship selection and application management platform designed to support academic institutions with scholarship publication, student applications, interviews, and selection results.

## Project Overview

This application centralizes scholarship program management and candidate selection in an academic environment. It is built as a data-driven workflow system with clear modules for content, application intake, and candidate evaluation.

The system works by:

- exposing public announcements and news pages to keep applicants informed
- letting administrators create and manage scholarships, scholarship types, and eligibility requirements
- allowing students to register and submit applications for selected scholarships
- tracking application status through review, interview scheduling, and final decision
- enabling staff and reviewers to log interview assessments and make selection decisions
- using fuzzy logic algorithms to preview and recommend ranked candidates before finalizing selections
- exporting results for reporting or external audit

## System Description

The platform is structured around four main domains:

1. Content Management
   - Announcements and news content are published for public visibility.
   - Only authorized staff can create, edit, and remove items.

2. Scholarship Management
   - Scholarships, scholarship types, and requirements are defined by administrators.
   - Scholarship requirements are used to validate applications and build selection criteria.

3. Application Workflow
   - Students submit applications linked to a scholarship and their student profile.
   - Applications flow through statuses such as waiting, processing, accepted, and rejected.
   - Administrators and staff can review applications and schedule interviews if needed.

4. Selection and Evaluation
   - Interviews are scheduled and assessments are recorded using interview assessment records.
   - A fuzzy selection module converts requirement data and interview results into ranked candidate recommendations.
   - Selection results can be exported in Excel or PDF format for reporting and decision support.

## Core Features

- Role-based access control for `admin`, `staf`, `kaprodi`, `wakil dekan 3`, and `mahasiswa`
- Public announcements and news listing
- Scholarship and requirement management
- Application intake, review, and status tracking
- Interview creation, scheduling, and result management
- Fuzzy selection preview and final application
- Exportable selection reports via Excel and PDF

## Technology Stack

- PHP 8.2
- Laravel 12
- Blade templating
- Tailwind CSS
- Alpine.js
- Vite
- `spatie/laravel-permission`
- `maatwebsite/excel`
- `barryvdh/laravel-dompdf`

## Installation Guide

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- A database server (MySQL, MariaDB, or SQLite)

### Setup Steps

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd sistem_seleksi_beasiswa
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Install JavaScript dependencies:

   ```bash
   npm install
   ```

4. Copy the environment file:

   ```bash
   cp .env.example .env
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Configure database credentials in `.env`.

   Example for MySQL:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

   Example for SQLite:

   ```bash
   touch database/database.sqlite
   ```

   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=${PWD}/database/database.sqlite
   ```

7. Run database migrations:

   ```bash
   php artisan migrate
   ```

8. Build front-end assets:

   ```bash
   npm run build
   ```

### Starting the Application

Run the local development server:

```bash
php artisan serve
```

For live asset rebuilding during development:

```bash
npm run dev
```

## Common Commands

- Run the application test suite:

  ```bash
  php artisan test --compact
  ```

- Clear caches and compiled files:

  ```bash
  php artisan optimize:clear
  ```

- Run the configured setup script:

  ```bash
  composer run setup
  ```

## Application Structure

- `app/Http/Controllers` — handles request logic
- `app/Models` — application data models
- `app/Exports` — export logic for selection results
- `app/Imports` — import preview and update processing
- `resources/views` — Blade templates for UI pages
- `routes/web.php` — route definitions and middleware protection

## Notes

- Make sure `.env` is configured correctly before migration.
- If you update frontend code, rerun `npm run build` or `npm run dev`.
- Role assignment is required for administrative features.

## License

This project is licensed under the MIT License.
