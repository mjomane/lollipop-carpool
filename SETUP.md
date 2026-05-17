# Lollipop Kid Carpool - Complete Setup Guide

## 📋 Project Overview

A safe, kid-focused carpooling app built with:
- **Backend**: Laravel + MySQL + REST API
- **Frontend**: React + TypeScript + Vite
- **Real-time**: Pusher (for live tracking)
- **Maps**: Google Maps (optional)

---

## 🚀 Installation Steps

### Prerequisites
- **PHP** 8.1+ with MySQL extension
- **Composer** (PHP package manager)
- **Node.js** 18+ with npm
- **MySQL** database running

### 1. Backend Setup

```bash
cd lollipop

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Run migrations and seeds
php artisan migrate --seed

# Start Laravel server
php artisan serve
# Backend runs at: http://localhost:8000
```

### 2. Frontend Setup

```bash
cd frontend

# Install Node dependencies
npm install

# Start development server
npm run dev
# Frontend runs at: http://localhost:5173
```

The frontend automatically proxies `/api` requests to `http://localhost:8000/api`.

---

## 🏗️ Project Structure

```
lollipop/
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── School.php
│   │   ├── Child.php
│   │   ├── AuthorizedContact.php
│   │   ├── Vehicle.php
│   │   ├── RideRequest.php
│   │   ├── Ride.php
│   │   ├── RideLocation.php
│   │   └── Notification.php
│   └── Http/Controllers/
│       ├── AuthController.php
│       ├── ParentController.php
│       ├── DriverController.php
│       ├── AdminController.php
│       ├── SchoolController.php
│       └── RideController.php
├── database/
│   ├── migrations/ (9 tables)
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
├── config/
│   ├── app.php
│   ├── auth.php
│   └── database.php
├── frontend/
│   ├── src/
│   │   ├── pages/
│   │   │   ├── Auth.tsx
│   │   │   ├── ParentDashboard.tsx
│   │   │   └── DriverDashboard.tsx
│   │   ├── components/
│   │   │   ├── AddChildForm.tsx
│   │   │   ├── RideRequestForm.tsx
│   │   │   └── AddVehicleForm.tsx
│   │   ├── services/
│   │   │   └── api.ts (Axios client)
│   │   ├── App.tsx
│   │   ├── main.tsx
│   │   └── index.css
│   ├── package.json
│   ├── vite.config.ts
│   └── tsconfig.json
└── composer.json
```

---

## 🔑 Key Features

### Backend API
- **Auth**: Register/login with role selection (parent, driver, admin)
- **Parents**: Add children, request rides, view ride history
- **Drivers**: Manage vehicles, accept rides, update location, view active rides
- **Schools**: Manage school profiles with zone/type
- **Rides**: Request, match, track, and complete rides
- **Admin**: Verify drivers, manage schools

### Frontend
- **Authentication**: Login/signup with email, password, role selection
- **Parent Dashboard**:
  - Add children with school assignment
  - View children profiles
  - Request rides (to/from school)
- **Driver Dashboard**:
  - Add and manage vehicles
  - View active rides
  - Pending verification status

---

## 🌐 API Routes

### Authentication
```
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/me
```

### Parent Routes
```
GET    /api/parents/children
POST   /api/parents/children
PUT    /api/parents/children/{id}
POST   /api/parents/ride-requests
GET    /api/parents/ride-requests
GET    /api/parents/rides
GET    /api/parents/rides/{id}
GET    /api/parents/notifications
```

### Driver Routes
```
GET    /api/drivers/profile
PUT    /api/drivers/profile
GET    /api/drivers/vehicles
POST   /api/drivers/vehicles
PUT    /api/drivers/vehicles/{id}
GET    /api/drivers/available-rides
POST   /api/drivers/rides/{id}/accept
POST   /api/drivers/rides/{id}/start
POST   /api/drivers/rides/{id}/complete
POST   /api/drivers/rides/{id}/location
GET    /api/drivers/rides/active
```

### Admin Routes
```
GET    /api/admin/drivers/pending
POST   /api/admin/drivers/{id}/approve
POST   /api/admin/drivers/{id}/reject
POST   /api/admin/schools
PUT    /api/admin/schools/{id}
```

### Common Routes
```
GET    /api/schools
GET    /api/schools/{id}
GET    /api/ride-status/{id}
```

---

## 🗄️ Database Schema

### users
- id, name, email, password, phone, role, is_verified, profile_completed

### schools
- id, name, address, city, state, postal_code, phone, email, type, zone, notes

### children
- id, parent_id, school_id, name, grade, dob, photo_url, special_needs, pickup_notes

### authorized_contacts
- id, child_id, name, relationship, phone, is_primary, allowed_for_pickup

### vehicles
- id, driver_id, make, model, year, plate_number, capacity, child_seat_count, insurance_status, registration_status

### ride_requests
- id, parent_id, child_id, school_id, type, pickup_address, pickup_lat, pickup_lng, dropoff_address, scheduled_at, status, notes

### rides
- id, ride_request_id, driver_id, vehicle_id, school_id, status, pickup_time, dropoff_time, eta, distance_km, fare_estimate, started_at, ended_at

### ride_locations
- id, ride_id, driver_lat, driver_lng, timestamp

### notifications
- id, user_id, type, title, body, data, is_read

---

## 🔐 Security Features

- **Sanctum Authentication**: Token-based API authentication
- **Role-based Access Control**: parent, driver, admin roles
- **Driver Verification**: Admin approval required before driver can accept rides
- **Authorized Contacts**: Only approved contacts can pick up children
- **Ride Authorization**: Parents must approve ride details
- **Password Hashing**: bcrypt for secure password storage

---

## 📱 Next Steps

1. **Customize**: Update school data, branding, and styling
2. **Add Maps**: Integrate Google Maps for address validation and route display
3. **Real-time Updates**: Add Pusher for live ride tracking
4. **Payment**: Integrate Stripe for ride payments
5. **Notifications**: Add Firebase Cloud Messaging for push notifications
6. **Testing**: Write unit and integration tests
7. **Deployment**: Deploy to production server

---

## 🛠️ Development

### Running Tests
```bash
php artisan test
npm run test
```

### Building Frontend for Production
```bash
cd frontend
npm run build
```

### Database Migrations
```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback
```

---

## 📝 Notes

- Frontend API calls automatically include Bearer token from localStorage
- API responses use consistent JSON format
- Error handling includes validation messages and status codes
- Database uses timestamps for audit trails
- Schools can be seeded with admin panel or API

---

## 🤝 Support

For issues or questions, refer to:
- Laravel docs: https://laravel.com/docs
- React docs: https://react.dev
- Vite docs: https://vitejs.dev
