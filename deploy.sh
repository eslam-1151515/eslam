#!/bin/bash
set -e

echo "🚀 Starting Deployment Process..."

# 1. Navigate to project root directory (in case script is run from elsewhere)
cd "$(dirname "$0")"

# 2. Put application into maintenance mode
echo "🚧 Putting application into maintenance mode..."
php artisan down --refresh=15 --retry=60 || true

# 3. Pull latest changes from git
echo "📥 Pulling latest updates from Git..."
git pull origin main

# 4. Install/Update PHP dependencies
echo "📦 Installing Composer dependencies..."
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 5. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 6. Clear and Cache configs, routes, and views
echo "🧹 Clearing and caching application configuration..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Build Frontend Assets
echo "✨ Installing NPM dependencies and building assets..."
npm ci
npm run build

# 8. Restart Queue Workers to apply code updates
echo "🔄 Restarting Queue Workers..."
php artisan queue:restart

# 9. Bring application back online
echo "✅ Bringing application online..."
php artisan up

echo "🎉 Deployment completed successfully!"
