FROM php:8.1
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
        libzip-dev \
        zip \
        git \
        openssl \
  && docker-php-ext-install -j$(nproc) zip mysqli
WORKDIR /root/

ENV COMPOSER_ALLOW_SUPERUSER 1

EXPOSE 8000

COPY . .
COPY configs/php.ini /usr/local/etc/php/conf.d/40-custom.ini

ENTRYPOINT ["bash", "setup.sh"]