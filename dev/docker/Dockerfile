FROM bliskapaczkapl/magento_base:7.2_2.3.0

MAINTAINER Mateusz Koszutowski <mkoszutowski@divante.pl>

RUN php ${magento_path}/bin/magento sampledata:deploy

# Install module 
COPY ./ ${magento_path}/app/code/Sendit/Bliskapaczka/

# Fix privileges
RUN find ${magento_path} -type d -exec chmod 777 {} \; && find ${magento_path} -type f -exec chmod 666 {} \; \
    && chown -R www-data:www-data ${magento_path}

RUN chown -R www-data:www-data /opt/composer/www-data
