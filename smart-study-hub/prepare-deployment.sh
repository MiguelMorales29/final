#!/bin/bash

# Smart Study Hub - Deployment Preparation Script
# This script prepares your Laravel app for deployment

echo "🚀 Preparing Smart Study Hub for deployment..."
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Creating from template..."
    cat > .env << 'EOF'
APP_NAME=Smart Study Hub
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@smartstudyhub.com"
MAIL_FROM_NAME="${APP_NAME}"

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

OPENAI_API_KEY=
EOF
    echo "✅ .env file created"
else
    echo "✅ .env file exists"
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate
    echo "✅ APP_KEY generated"
else
    echo "✅ APP_KEY already set"
fi

# Install dependencies
echo ""
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo ""
echo "📦 Installing NPM dependencies..."
npm ci

# Build assets
echo ""
echo "🏗️  Building assets..."
npm run build

# Clear and cache config
echo ""
echo "⚙️  Optimizing Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Create storage link
echo ""
echo "🔗 Creating storage link..."
php artisan storage:link || echo "⚠️  Storage link already exists"

# Run migrations (optional - comment out if you don't want to run locally)
# echo ""
# echo "🗄️  Running migrations..."
# php artisan migrate --force

echo ""
echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps:"
echo "1. Review DEPLOYMENT_QUICKSTART.md"
echo "2. Push code to GitHub"
echo "3. Deploy on Render or Railway"
echo "4. Set environment variables"
echo "5. Run migrations on hosting platform"
echo ""
echo "🎉 Good luck with your deployment!"

