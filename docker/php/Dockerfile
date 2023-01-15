FROM php:8.2-fpm

WORKDIR "/home/php.quest"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update

# ------------------------------------------------------------------------------
# Installation Dependencies
# ------------------------------------------------------------------------------

RUN apt-get update \
    && pecl channel-update pecl.php.net \
    && apt-get -y --no-install-recommends install \
      libicu-dev \
      libzip-dev \
      unzip \
      git \
      curl

# Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

# APCU
RUN pecl install apcu
RUN docker-php-ext-enable apcu

# INTL
RUN docker-php-ext-install intl
RUN docker-php-ext-enable intl

# Zip
RUN docker-php-ext-install zip
RUN docker-php-ext-enable zip

# Opcache
RUN docker-php-ext-install opcache

# Sockets
RUN docker-php-ext-install sockets

# ------------------------------------------------------------------------------
# Cleanup Dependencies
# ------------------------------------------------------------------------------

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && ln -sf /usr/share/zoneinfo/Etc/UTC /etc/localtime
