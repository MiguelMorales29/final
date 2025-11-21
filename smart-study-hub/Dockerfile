FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    postgresql-client \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (skip scripts to avoid artisan commands during build)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Copy application files (needed for artisan commands)
COPY . .

# Copy .env.example to .env for build (Laravel needs it for artisan commands)
RUN cp .env.example .env || true

# Set a temporary APP_KEY for build (will be overridden by environment variables at runtime)
RUN php artisan key:generate --ansi || echo "APP_KEY=base64:temp_key_for_build_only_replace_in_production" >> .env

# Run post-install scripts now that we have APP_KEY
RUN composer run-script post-autoload-dump --no-interaction || true

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port (Render will set PORT env variable)
EXPOSE 8000

# Create startup script that runs migrations and starts server
RUN echo '#!/bin/sh\n\
set -e\n\
# Wait a moment for database to be ready\n\
sleep 2\n\
# Function to update or add env variable in .env file\n\
update_env() {\n\
  local key=$1\n\
  local value=$2\n\
  if grep -q "^${key}=" .env 2>/dev/null; then\n\
    # Update existing value (handle special characters in value)\n\
    sed -i "s|^${key}=.*|${key}=${value}|" .env\n\
  else\n\
    # Add new value\n\
    echo "${key}=${value}" >> .env\n\
  fi\n\
}\n\
# Update .env with runtime environment variables (Render sets these)\n\
[ -n "$DB_CONNECTION" ] && update_env "DB_CONNECTION" "$DB_CONNECTION"\n\
[ -n "$DB_HOST" ] && update_env "DB_HOST" "$DB_HOST"\n\
[ -n "$DB_PORT" ] && update_env "DB_PORT" "$DB_PORT"\n\
[ -n "$DB_DATABASE" ] && update_env "DB_DATABASE" "$DB_DATABASE"\n\
[ -n "$DB_USERNAME" ] && update_env "DB_USERNAME" "$DB_USERNAME"\n\
[ -n "$DB_PASSWORD" ] && update_env "DB_PASSWORD" "$DB_PASSWORD"\n\
[ -n "$APP_KEY" ] && update_env "APP_KEY" "$APP_KEY"\n\
[ -n "$APP_ENV" ] && update_env "APP_ENV" "$APP_ENV"\n\
[ -n "$APP_DEBUG" ] && update_env "APP_DEBUG" "$APP_DEBUG"\n\
[ -n "$APP_URL" ] && update_env "APP_URL" "$APP_URL"\n\
# Ensure APP_URL uses HTTPS if not explicitly set\n\
if [ -z "$APP_URL" ]; then\n\
  update_env "APP_URL" "https://smart-study-hub-a5xr.onrender.com"\n\
fi\n\
# Clear config cache to ensure new env vars are loaded\n\
php artisan config:clear || true\n\
# Reset and run migrations (fresh drops all tables and re-runs)\n\
# This ensures clean database state after fixing migration syntax\n\
php artisan migrate:fresh --force || php artisan migrate --force || true\n\
# Create storage link\n\
php artisan storage:link || true\n\
# Start PHP server\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}' > /start.sh && chmod +x /start.sh

# Use startup script
CMD ["/start.sh"]

