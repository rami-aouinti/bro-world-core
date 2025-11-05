FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git bash icu-dev libzip-dev libpq-dev oniguruma-dev libsodium-dev curl supervisor \
    rabbitmq-c-dev autoconf make gcc g++ \
    && mkdir -p /var/log/supervisor \
    && printf "" | pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install intl pdo pdo_pgsql opcache sodium

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY conf/supervisord.conf /etc/supervisor/supervisord.conf
COPY conf/supervisor-programs.conf /etc/supervisor/conf.d/programs.conf

CMD ["/bin/sh", "-lc", "php-fpm -D && supervisord -n -c /etc/supervisor/supervisord.conf"]
