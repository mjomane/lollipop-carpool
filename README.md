# Lollipop Kid Carpool

Laravel backend scaffold for a kid-safe carpooling app.

## Project overview

This project includes a backend schema and REST API design for:
- parents, drivers, and admins
- children and schools
- vehicles and ride requests
- live ride tracking and notifications

## Setup

1. Install PHP and Composer
2. Run:
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```
3. Configure your database in `.env`
4. Run migrations:
   ```bash
   php artisan migrate
   ```

## Structure

- `app/Models` - Eloquent models
- `app/Http/Controllers` - API controllers
- `database/migrations` - schema definitions
- `routes/api.php` - API routes
- `frontend/` - React frontend scaffold
  - `src/pages/` - Auth, ParentDashboard, DriverDashboard
  - `src/components/` - AddChildForm, RideRequestForm, AddVehicleForm
  - `src/services/` - API client with auth interceptors

## Features

### Backend (Laravel)
- User authentication (parents, drivers, admins)
- Child profiles with school assignment
- Vehicle management for drivers
- Ride requests and matching
- Real-time ride tracking
- Admin driver verification

### Frontend (React + TypeScript)
- Responsive auth with role selection
- Parent dashboard: add children, request rides
- Driver dashboard: manage vehicles, view rides
- Modal forms for data entry
- API integration with error handling

## Notes

This scaffold is intended to bootstrap the app. The next step is to install Laravel dependencies and add authentication, validation, and business logic.
