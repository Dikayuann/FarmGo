FROM php:8.3-cli

# System deps untuk intl/zip/gd
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev \
    libicu-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install intl zip gd \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependencies (tanpa dev biar lebih ringan)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Expose & run Laravel public
ENV PORT=8080
CMD php -S 0.0.0.0:$PORT -t public
