#!/bin/bash
set -e

echo "🚀 Starting Zero-Downtime Deployment..."

# Configuration
REPO_TOKEN=$1
REPO_URL="https://x-access-token:${REPO_TOKEN}@github.com/fast-order-eg/fast-order.git"
BASE_DIR="/home/fast-order-eg.tech/deploy"
RELEASES_DIR="$BASE_DIR/releases"
SHARED_DIR="$BASE_DIR/shared"
CURRENT_DIR="$BASE_DIR/current"
RELEASE_NAME=$(date +"%Y%m%d%H%M%S")
RELEASE_DIR="$RELEASES_DIR/$RELEASE_NAME"

echo "📂 Creating new release directory: $RELEASE_DIR"
mkdir -p "$RELEASE_DIR"

echo "📥 Cloning repository..."
git clone --depth 1 "$REPO_URL" "$RELEASE_DIR"

echo "🔗 Linking shared files..."
# Link .env
if [ -f "$SHARED_DIR/.env" ]; then
    ln -nfs "$SHARED_DIR/.env" "$RELEASE_DIR/.env"
else
    echo "⚠️ WARNING: .env not found in shared directory. Copying from repository."
    cp "$RELEASE_DIR/.env.example" "$SHARED_DIR/.env"
    ln -nfs "$SHARED_DIR/.env" "$RELEASE_DIR/.env"
fi

# Link storage
rm -rf "$RELEASE_DIR/storage"
ln -nfs "$SHARED_DIR/storage" "$RELEASE_DIR/storage"

echo "📦 Installing Composer dependencies..."
cd "$RELEASE_DIR"
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

echo "✨ Building Frontend Assets..."
npm install
npm run build

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "🔗 Linking public storage..."
php artisan storage:link || true

echo "🧹 Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "🔄 Swapping symlink for Zero-Downtime..."
ln -nfs "$RELEASE_DIR" "$CURRENT_DIR"

echo "🔄 Restarting Queue Workers..."
php artisan queue:restart || true
# Restart OpenLiteSpeed detached PHP processes
killall -9 lsphp || true

echo "🔐 Fixing file permissions..."
chown -R fasto5299:nobody "$RELEASE_DIR"

echo "🧹 Cleaning up old releases (keeping last 3)..."
cd "$RELEASES_DIR"
ls -1t | tail -n +4 | xargs -r rm -rf

echo "🎉 Zero-Downtime Deployment completed successfully!"
