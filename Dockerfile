FROM php:8.4-cli

WORKDIR /app

# パッケージ
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# コピー
COPY . .

# composer install 先にやる（重要）
RUN composer install --no-dev --optimize-autoloader

# sqlite
RUN mkdir -p database
RUN touch database/database.sqlite

# env
RUN echo "APP_NAME=RenderMonitor" > .env
RUN echo "APP_ENV=production" >> .env
RUN echo "APP_DEBUG=false" >> .env
RUN echo "APP_URL=http://localhost" >> .env
RUN echo "DB_CONNECTION=sqlite" >> .env
RUN echo "SESSION_DRIVER=cookie" >> .env
RUN echo "CACHE_STORE=array" >> .env
RUN echo "QUEUE_CONNECTION=sync" >> .env

# key（composer後）
RUN php artisan key:generate

# migrate
RUN php artisan migrate --force

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000