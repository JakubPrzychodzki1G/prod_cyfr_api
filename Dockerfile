FROM php:8.2-fpm-bookworm
ARG TIMEZONE

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \ 
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    libpq-dev \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype 

RUN docker-php-ext-install \
    zip xsl gd intl opcache exif mbstring
RUN docker-php-ext-install pdo pdo_pgsql

COPY bin /var/www/symfony/bin
COPY config /var/www/symfony/config
COPY migrations /var/www/symfony/migrations
COPY public /var/www/symfony/public
COPY src /var/www/symfony/src
COPY templates /var/www/symfony/templates
COPY .env /var/www/symfony/
COPY composer.json /var/www/symfony/
COPY composer.lock /var/www/symfony/
COPY symfony.lock /var/www/symfony/
COPY poseidon-app.json /var/www/symfony/

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker-entrypoint.sh /usr/local/bin
RUN chmod 755 /usr/local/bin/docker-entrypoint.sh
RUN chmod 777 /var/www/symfony/public/media
RUN chmod 777 /var/www/symfony/public/tmp

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /var/www/symfony