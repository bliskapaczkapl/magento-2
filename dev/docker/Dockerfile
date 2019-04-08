FROM bliskapaczkapl/magento_base:7.2_2.3.0

MAINTAINER Mateusz Koszutowski <mkoszutowski@divante.pl>

ARG commit

USER www-data

RUN if [ -n "$tag" ] ; then composer require sendit/bliskapaczka-magento-2 $tag ; else composer require sendit/bliskapaczka-magento-2:dev-master#$commit ; fi \
	&& php ${magento_path}/bin/magento sampledata:deploy

USER root
