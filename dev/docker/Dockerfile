FROM php:7.1-fpm

MAINTAINER Mateusz Koszutowski <mkoszutowski@divante.pl>

ENV magento_path /var/www/magento
WORKDIR ${magento_path}

RUN apt-get update \
    && apt-get install -y \
        python-setuptools \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmcrypt-dev \
        libicu-dev \
        libxslt-dev \
        cron \
        mc \
        nano \
        mysql-client \
    && docker-php-ext-install \
        pdo_mysql \
        mysqli \
        mcrypt \
        intl \
        xsl \
        soap \
        bcmath \
        zip \
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && apt-get purge -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmcrypt-dev \
        libicu-dev \
        libxslt-dev \
    && apt-get install -y gnupg \
    && curl http://nginx.org/keys/nginx_signing.key | apt-key add - \
    && echo "deb http://nginx.org/packages/mainline/debian/ stretch nginx" > /etc/apt/sources.list.d/nginx.list \
    && apt-get update \
    && apt-get install -y nginx \
    && rm -f /etc/apt/sources.list.d/nginx.list \
    && apt-key del 7BD9BF62 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN rm -rf /var/www/html \
    && rm -f /etc/nginx/conf.d/default.conf

COPY dev/docker/nginx/conf.d /etc/nginx/conf.d
COPY dev/docker/php/conf.d/memory-limit-php.ini /usr/local/etc/php/conf.d/memory-limit-php.ini
COPY dev/docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN usermod -s /bin/bash www-data

# Install composer
RUN (curl -sS https://getcomposer.org/installer | php) || exit 1 \
    && mv composer.phar /usr/local/bin/composer.phar

# Create composer wrapper
RUN echo '#!/usr/bin/env bash' >> /usr/local/bin/composer \
    && echo 'COMPOSER_MEMORY_LIMIT=-1 COMPOSER_HOME=/opt/composer/www-data /usr/local/bin/composer.phar $@' >> /usr/local/bin/composer \
    && chmod 0755 /usr/local/bin/composer \
    && /usr/local/bin/composer config -g http-basic.repo.magento.com bc4c30862f0179e4fb6588d87b42fabc cb1289ea2905db7d27baffcd717c6ed8 \
    && /usr/local/bin/composer -q create-project --repository-url=https://repo.magento.com/ magento/project-community-edition ${magento_path} \
    && /usr/local/bin/composer config http-basic.repo.magento.com bc4c30862f0179e4fb6588d87b42fabc cb1289ea2905db7d27baffcd717c6ed8

RUN php ${magento_path}/bin/magento sampledata:deploy

COPY dev/docker/magento/styles.css ${magento_path}/pub/media/styles.css

# Install cron file
COPY dev/docker/magento/crontab /tmp/crontab
RUN crontab -u www-data /tmp/crontab

# Install module 
COPY ./ ${magento_path}/app/code/Sendit/Bliskapaczka/

# Fix privileges
RUN find ${magento_path} -type d -exec chmod 777 {} \; && find ${magento_path} -type f -exec chmod 666 {} \; \
    && chown -R www-data:www-data ${magento_path}

RUN chown -R www-data:www-data /opt/composer/www-data

# Install PECL extensions
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY dev/docker/run /opt/run

EXPOSE 80

CMD bash /opt/run
