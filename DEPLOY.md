# 🚀 Deploy Lollipop to Railway (Free Tier)

Railway is perfect for Laravel + React apps with a generous free tier.

## 1. Create Railway Account

Go to [railway.app](https://railway.app) and sign up with GitHub.

## 2. Install Railway CLI

```bash
npm install -g @railway/cli
railway login
```

## 3. Prepare Your Project

### Backend Setup
```bash
cd lollipop

# Create Railway config
echo '{
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT"
  }
}' > railway.json

# Create Procfile
echo 'web: php artisan serve --host=0.0.0.0 --port=$PORT' > Procfile
```

### Database Setup
Railway provides PostgreSQL by default. Update your Laravel config:

**config/database.php** - Add PostgreSQL connection:
```php
'pgsql' => [
    'driver' => 'pgsql',
    'host' => env('PGHOST'),
    'port' => env('PGPORT'),
    'database' => env('PGDATABASE'),
    'username' => env('PGUSER'),
    'password' => env('PGPASSWORD'),
    'charset' => 'utf8',
    'prefix' => '',
    'schema' => 'public',
    'sslmode' => 'prefer',
],
```

## 4. Deploy Backend

```bash
# Initialize Railway project
railway init

# Add PostgreSQL database
railway add postgresql

# Set environment variables
railway variables set APP_NAME=Lollipop
railway variables set APP_ENV=production
railway variables set APP_KEY=$(php artisan key:generate --show)
railway variables set DB_CONNECTION=pgsql

# Deploy
railway up
```

## 5. Deploy Frontend

### Option A: Deploy to Vercel (Free)

```bash
cd frontend

# Install Vercel CLI
npm install -g vercel

# Deploy
vercel --prod

# Set environment variable for API URL
vercel env add VITE_API_URL
# Enter your Railway backend URL: https://your-app.railway.app/api
```

### Option B: Deploy to Railway (Same Project)

```bash
cd frontend

# Create frontend railway config
echo '{
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "npm run preview"
  }
}' > railway.json

# Deploy frontend to same project
railway up
```

## 6. Domain & SSL

Railway provides free SSL and custom domains:
```bash
railway domain
# Follow prompts to add custom domain
```

## 7. Final Setup

### Run Migrations on Railway
```bash
railway run php artisan migrate --seed
```

### Test Your Live App
- Backend: `https://your-app.railway.app`
- Frontend: `https://your-frontend.vercel.app` or Railway URL

---

# 🚀 Alternative: Deploy to Render (Free Tier)

## Backend on Render

1. Go to [render.com](https://render.com) and sign up
2. Create new **Web Service** from GitHub repo
3. Set build command: `composer install && php artisan key:generate`
4. Set start command: `php artisan serve --host=0.0.0.0 --port=$PORT`
5. Add PostgreSQL database in Render dashboard
6. Set environment variables:
   - `APP_ENV=production`
   - `DB_CONNECTION=pgsql`
   - Database connection vars from Render

## Frontend on Vercel

Same as above - deploy React app to Vercel with API URL pointing to Render backend.

---

# 📊 Cost Comparison

| Platform | Free Tier | Paid Plan |
|----------|-----------|-----------|
| **Railway** | 512MB RAM, 1GB disk, 100 hours/month | $5/month per service |
| **Render** | 750 hours/month, 512MB RAM | $7/month per service |
| **Vercel** | 100GB bandwidth, unlimited static sites | $0 for hobby, $20/month pro |
| **Heroku** | 550 hours/month (eco dyno) | $7/month per dyno |

---

# 🎯 Recommended Stack

**Production Ready:**
- **Backend**: Railway ($5/month) or Render ($7/month)
- **Database**: PostgreSQL (included)
- **Frontend**: Vercel (free)
- **Domain**: Namecheap (~$10/year) or Railway custom domain

**Total Cost**: ~$5-7/month + domain

---

# 🔧 Environment Variables Needed

```bash
# Laravel
APP_NAME=Lollipop
APP_ENV=production
APP_KEY=your-generated-key
APP_URL=https://your-domain.com

# Database (Railway/Render provides these)
DB_CONNECTION=pgsql
PGHOST=your-db-host
PGPORT=5432
PGDATABASE=your-db-name
PGUSER=your-db-user
PGPASSWORD=your-db-password

# Frontend
VITE_API_URL=https://your-backend-domain.com/api
```

---

# 📝 Deployment Checklist

- [ ] Create Railway/Render account
- [ ] Push code to GitHub
- [ ] Deploy backend with database
- [ ] Run migrations: `php artisan migrate --seed`
- [ ] Deploy frontend to Vercel
- [ ] Set custom domain
- [ ] Test user registration
- [ ] Test ride request flow
- [ ] Enable SSL (automatic)
- [ ] Monitor logs and performance

---

# 🌐 Your App Will Be Live At

- **Frontend**: `https://your-app.vercel.app`
- **Backend API**: `https://your-backend.railway.app/api`
- **Admin Panel**: `https://your-backend.railway.app/admin` (if you build it)

Ready to deploy? I can help you with the specific commands for your chosen platform!

---

# 💳 PayFast Payment Integration

## Features Added
- ✅ Payment model and migration
- ✅ PayFast API integration
- ✅ Payment creation and processing
- ✅ Webhook handling for payment confirmation
- ✅ Frontend payment UI
- ✅ Ride payment status tracking

## Payment Flow
1. Parent completes ride → sees "Pay" button
2. Clicks pay → redirected to PayFast
3. PayFast processes payment → sends webhook to backend
4. Backend updates payment status → ride marked as paid

## PayFast Setup

### Get PayFast Credentials
1. Sign up at [payfast.co.za](https://www.payfast.co.za)
2. Get your **Merchant ID** and **Merchant Key**
3. Set up your **passphrase** (optional but recommended)
4. Configure return/cancel/notify URLs in your PayFast settings

### Environment Variables for Railway
```bash
# PayFast Configuration
railway variables set PAYFAST_MERCHANT_ID=your_payfast_merchant_id
railway variables set PAYFAST_MERCHANT_KEY=your_payfast_merchant_key
railway variables set PAYFAST_PASSPHRASE=your_payfast_passphrase
railway variables set PAYFAST_RETURN_URL=https://your-frontend.vercel.app/payment/success
railway variables set PAYFAST_CANCEL_URL=https://your-frontend.vercel.app/payment/cancel
railway variables set PAYFAST_NOTIFY_URL=https://your-backend.railway.app/api/payments/notify
```

## Testing Payments
Use PayFast sandbox for testing:
- Sandbox URL: `https://sandbox.payfast.co.za`
- Test cards available in PayFast docs

## PayFast Fees
- Transaction fees: ~2.9% + R2.50 per transaction
- No monthly fees
- Instant payouts