FROM php:8.3-cli

# Install system deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev \
    libicu-dev \
    default-mysql-client \
    libpng-dev libjpeg-dev libfreetype6-dev \
    curl ca-certificates gnupg \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    pdo_mysql \
    intl \
    zip \
    gd \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Node + Vite build (IMPORTANT: ENV must be separate, no trailing "\")
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get update \
 && apt-get install -y nodejs \
 && npm ci \
 && npm run build \
 && rm -rf /var/lib/apt/lists/*

ENV PORT=8080
CMD ["sh", "-lc", "php -S 0.0.0.0:${PORT} -t public"]
