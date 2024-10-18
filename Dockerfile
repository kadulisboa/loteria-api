FROM php:8.2-fpm-alpine

# Instalar dependências necessárias e extensões do PHP para PostgreSQL
RUN apk update && apk add --no-cache \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN apk update && apk add --no-cache \
    sqlite-libs \
    sqlite-dev \
    autoconf \
    g++ \
    make

# Instala a extensão PDO SQLite
RUN docker-php-ext-install pdo_sqlite

WORKDIR /var/www/html

COPY . .

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer && \
    composer install