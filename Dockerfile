FROM php:8.4-cli

WORKDIR /app

# 必要パッケージ
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ソースコピー
COPY . .

# composer install
RUN composer install --no-dev --optimize-autoloader

# sqlite 作成
RUN mkdir -p database
RUN touch database/database.sqlite

# .env 作成
RUN echo "APP_NAME=RenderMonitor" > .env
RUN echo "APP_ENV=production" >> .env
RUN echo "APP_KEY=" >> .env
RUN echo "APP_DEBUG=false" >> .env
RUN echo "APP_URL=http://localhost" >> .env
RUN echo "DB_CONNECTION=sqlite" >> .env
RUN echo "SESSION_DRIVER=cookie" >> .env
RUN echo "CACHE_STORE=array" >> .env
RUN echo "QUEUE_CONNECTION=sync" >> .env

# APP KEY生成
RUN php artisan key:generate

# migrate
RUN php artisan migrate --force

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000