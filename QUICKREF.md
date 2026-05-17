# Lollipop - Quick Reference

## 🚀 Start Development (2 terminals)

**Terminal 1 - Backend:**
```bash
cd lollipop
php artisan serve
# http://localhost:8000
```

**Terminal 2 - Frontend:**
```bash
cd lollipop/frontend
npm run dev
# http://localhost:5173
```

## 📊 User Roles

| Role | Features |
|------|----------|
| **Parent** | Add children, request rides, view history, get notifications |
| **Driver** | Add vehicles, accept rides, update location, earn rides |
| **Admin** | Approve drivers, manage schools, view system analytics |

## 🎯 Core User Flows

### Parent Journey
1. Sign up as parent
2. Add children with school assignment
3. Add authorized pickup contacts
4. Request ride (to_school / from_school)
5. Receive ride confirmation + driver info
6. Track ride live
7. Rate driver + provide feedback

### Driver Journey
1. Sign up as driver
2. Add vehicle (make, model, seats)
3. Await admin verification
4. View available rides
5. Accept ride request
6. Navigate to pickup
7. Start ride, track location
8. Complete ride
9. Receive payment + rating

### Admin Journey
1. View pending driver approvals
2. Review driver documents
3. Approve/reject drivers
4. Manage school database
5. Monitor system usage

## 📱 Frontend Pages

- `/` - Auth (login/signup)
- `/parent` - Parent dashboard
- `/driver` - Driver dashboard

## 🔗 Key API Endpoints

**Auth**
- `POST /api/auth/register`
- `POST /api/auth/login`

**Ride Operations**
- `POST /api/parents/ride-requests` (request)
- `GET /api/drivers/available-rides` (list)
- `POST /api/drivers/rides/{id}/accept` (accept)
- `POST /api/drivers/rides/{id}/location` (track)
- `POST /api/drivers/rides/{id}/complete` (end)

## 📦 Tech Stack Summary

| Layer | Tech |
|-------|------|
| Frontend | React 18 + TypeScript + Vite |
| Backend | Laravel 10 + PHP 8.1 |
| Database | MySQL 8.0+ |
| Auth | Laravel Sanctum |
| Maps | Google Maps API (optional) |
| Real-time | Pusher (optional) |
| Notifications | Firebase (optional) |

## 🗂️ File Organization

```
lollipop/
├── app/Models/          → Data models (User, Ride, etc.)
├── app/Http/Controllers/→ API endpoints logic
├── database/migrations/ → Database schema
├── routes/api.php       → API route definitions
├── frontend/src/pages/  → React pages
├── frontend/src/components/ → React components
├── frontend/src/services/ → API client
└── SETUP.md            → Full setup guide
```

## 🔐 Auth Token

Token stored in `localStorage.auth_token` after login. Auto-included in API requests.

## 🎨 UI Colors

- Primary: #667eea (purple)
- Driver: #f5576c (red)
- Success: #2ecc71 (green)
- Warning: #ffc107 (yellow)

## 📋 Database Tables

1. **users** - Parents, drivers, admins
2. **schools** - School directory
3. **children** - Child profiles
4. **authorized_contacts** - Pickup people
5. **vehicles** - Driver vehicles
6. **ride_requests** - Ride bookings
7. **rides** - Active/completed rides
8. **ride_locations** - GPS tracking
9. **notifications** - User alerts

## ✅ Checklist for Launch

- [ ] Install PHP + Composer
- [ ] Install Node.js + npm
- [ ] Create MySQL database
- [ ] Run `composer install`
- [ ] Run `npm install` in frontend/
- [ ] Run migrations: `php artisan migrate --seed`
- [ ] Start backend: `php artisan serve`
- [ ] Start frontend: `npm run dev`
- [ ] Test login as parent/driver
- [ ] Add test data (school, children, vehicles)
- [ ] Verify ride request flow
- [ ] Deploy to production

## 🆘 Troubleshooting

| Issue | Solution |
|-------|----------|
| "php: command not found" | Add PHP to PATH or use full path |
| "npm: command not found" | Install Node.js or add npm to PATH |
| API 401 errors | Token expired, logout and login again |
| CORS errors | Check backend proxy config in vite.config.ts |
| DB connection failed | Verify .env DB credentials |
