ARG DOCKER_PHP_VERSION

FROM php:8-fpm-alpine

RUN apk add --update --no-cache icu-libs \
    autoconf \
    build-base \
    libintl \
    make \
    mysql-client \
    openssh-client \
    wget \
    bash \
    curl \
    git \
    gcc \
    freetype-dev \
    gettext-dev \
    icu-dev \
    libcurl \
    libffi-dev \
    libgcrypt-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libmcrypt-dev \
    libressl-dev \
    libxslt-dev \
    libzip-dev \
    linux-headers \
    libxml2-dev \
    ldb-dev \
    libmemcached-dev

#  Install php extensions
RUN php -m && \
    docker-php-ext-configure bcmath --enable-bcmath && \
    docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      --with-jpeg=/usr/include/ && \
    docker-php-ext-configure gettext && \
    docker-php-ext-configure intl --enable-intl && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-configure pcntl --enable-pcntl && \
    docker-php-ext-configure soap && \
    docker-php-ext-configure zip && \
    docker-php-ext-install exif \
        mysqli \
        opcache \
        xsl \
        bcmath \
        gd \
        gettext \
        intl \
        pcntl \
        soap \
        zip \
        calendar \
        pdo_mysql \
        sockets && \
    pecl install redis memcache-8.0 && \
    docker-php-ext-enable redis memcache

# Install Memcached
RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/v3.1.5.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached \
    && rm /tmp/memcached.tar.gz

# Fix work iconv library with alphine
RUN apk add --upgrade gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so

# Clean
RUN rm -rf /var/cache/apk/* && docker-php-source delete

USER root

RUN cd ~ \
    && mkdir -p /usr/local/bin \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --2

USER www-data:www-data

WORKDIR /var/www/stocker/
