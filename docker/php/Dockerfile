# 開発環境(localhost)↓
FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www

# デプロイ用↓

# FROM php:8.0-fpm


# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     libzip-dev \
#     && docker-php-ext-install zip pdo pdo_mysql


# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# WORKDIR /var/www

# COPY ./src /var/www 
# COPY ./src/.env.example /var/www/.env


# RUN chown -R www-data:www-data /var/www && \
#     chmod -R 755 /var/www


# RUN composer clear-cache && \
#     composer install --no-dev --optimize-autoloader || { echo 'Composer install failed'; exit 1; }


# RUN test -f /var/www/.env || { echo ".env file is missing"; exit 1; }


# RUN php artisan key:generate && \\
#     php artisan config:cache && \
#     php artisan view:cache

# EXPOSE 80


# CMD ["php-fpm"]
