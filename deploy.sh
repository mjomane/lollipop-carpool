#!/bin/bash

# 🚀 Quick Deploy Script for Railway
# Run this after pushing your code to GitHub

echo "🚀 Starting Lollipop Deployment to Railway..."

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo "Installing Railway CLI..."
    npm install -g @railway/cli
fi

# Login to Railway
echo "Logging into Railway..."
railway login

# Initialize project
echo "Initializing Railway project..."
railway init lollipop-carpool

# Add PostgreSQL database
echo "Adding PostgreSQL database..."
railway add postgresql

# Set environment variables
echo "Setting environment variables..."
railway variables set APP_NAME=Lollipop
railway variables set APP_ENV=production
railway variables set APP_KEY=$(php artisan key:generate --show)
railway variables set DB_CONNECTION=pgsql

# Deploy backend
echo "Deploying backend..."
railway up

# Get the backend URL
BACKEND_URL=$(railway domain)
echo "Backend deployed at: $BACKEND_URL"

# Deploy frontend to Vercel
echo "Deploying frontend to Vercel..."
cd frontend

if ! command -v vercel &> /dev/null; then
    npm install -g vercel
fi

vercel --prod

# Set API URL for frontend
echo "Setting API URL for frontend..."
vercel env add VITE_API_URL
echo "Enter this URL when prompted: $BACKEND_URL/api"

echo "🎉 Deployment complete!"
echo "Backend: $BACKEND_URL"
echo "Frontend: Check Vercel dashboard for URL"
echo ""
echo "Next steps:"
echo "1. Run migrations: railway run php artisan migrate --seed"
echo "2. Test your app!"
echo "3. Add custom domain if needed"