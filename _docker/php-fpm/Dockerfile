FROM php:7.2-fpm

MAINTAINER Julien Guittard <julien.guittard@me.com>

ADD ./jgdotcom.ini /usr/local/etc/php/conf.d
ADD ./jgdotcom.pool.conf /usr/local/etc/php-fpm.d/

RUN mkdir -p /usr/local/bin

RUN apt-get update \
    && buildDeps=" \
        git \
        libpq-dev \
        curl \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libssl-dev \
        libicu-dev \
        zlib1g-dev \
    " \
    && apt-get install -y $buildDeps --no-install-recommends \
    \
    && docker-php-source extract \
    && docker-php-ext-configure gd \
           --with-freetype-dir=/usr/include/freetype2

# Install extensions using the helper script provided by the base image
RUN docker-php-ext-install \
    gd \
    intl \
    opcache

# Install xdebug
RUN pecl install xdebug-2.6.0beta1 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.default_enable = on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_enable = on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart = on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back = off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.profiler_enable = on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_log='/tmp/xdebug.log'" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_host = 127.0.0.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN docker-php-source delete \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false $buildDeps

RUN rm -r /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data

# RUN echo 'alias expressive="php app/console"' >> ~/.bashrc

COPY entrypoint.sh /opt/entrypoint.sh
RUN chmod a+x /opt/entrypoint.sh

ENTRYPOINT ["/opt/entrypoint.sh"]

WORKDIR /var/www

CMD ["php-fpm"]