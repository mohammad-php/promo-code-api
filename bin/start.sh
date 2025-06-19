#!/bin/bash

set -euo pipefail

export $(grep -v '^#' .env | xargs)

APP_CONTAINER="promo_app"
DB_CONTAINER="mysql"
TEST_DB="promo_api_testing"

echo "⏳ Initializing Laravel Docker Environment..."

# Ensure .env exists
if [ ! -f .env ]; then
  echo "⚙️  Copying .env.example to .env..."
  cp .env.example .env
fi

echo "🐳 Building containers..."
docker-compose up --build -d

echo "🕒 Waiting for MySQL to be ready..."
until docker exec "$DB_CONTAINER" mysqladmin ping -h"127.0.0.1" -u "$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  echo -n "."; sleep 2
done

echo ""
echo "🛠️  Ensuring test DB '$TEST_DB' exists..."
docker exec "$DB_CONTAINER" mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $TEST_DB;"

echo "📂 Migrating Main DB..."
docker exec "$APP_CONTAINER" php artisan migrate --seed

echo "📂 Migrating Test DB..."
docker exec "$APP_CONTAINER" php artisan migrate --seed --env=testing

echo "📝 Generating API documentation..."
docker exec "$APP_CONTAINER" php artisan scribe:generate --force

if [[ "${1:-}" == "--test" ]]; then
  echo "🧪 Running Pest tests..."
  docker exec "$APP_CONTAINER" ./vendor/bin/pest
fi

echo ""
echo "✅ Setup complete! Visit: http://localhost:8080/docs"
