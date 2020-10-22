FROM php:7.4-fpm-alpine

COPY --from=composer:1.9 /usr/bin/composer /usr/local/bin/composer

RUN apk add --no-cache \
        ca-certificates \
        icu-libs \
        git \
        unzip \
        rabbitmq-c-dev \
        zlib-dev \
        libmemcached-dev \
        libxml2-dev \
        # gd
        freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
        imagemagick-dev imagemagick libzip-dev libtool make \
        openssh-client && \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev && \
    docker-php-ext-configure gd \
        --enable-gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ && \
    NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
    docker-php-ext-install -j${NPROC} gd && \
    docker-php-ext-install \
        intl \
        zip \
        soap \
        bcmath \
        pdo \
        sockets \
        pdo_mysql && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    pecl install amqp && \
    docker-php-ext-enable amqp && \
    pecl install xdebug-2.9.2 && \
    docker-php-ext-enable xdebug && \
    echo "short_open_tag = off" >> /usr/local/etc/php/php.ini && \
    echo "date.timezone = Europe/Luxembourg" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "opcache.max_accelerated_files = 20000" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "realpath_cache_size=4096K" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "realpath_cache_ttl=600" >> /usr/local/etc/php/conf.d/symfony.ini && \
    apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev && \
    apk del .build-deps
