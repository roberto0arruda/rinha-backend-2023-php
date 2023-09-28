FROM php:7.4-apache

ARG timezone

ENV TIMEZONE=${timezone:-"America/Sao_Paulo"}

# Install dependencies some base extensions
RUN apt-get update -y && apt-get upgrade -y && apt-get install -y \
    build-essential \
    libpq-dev \
    postgresql-client \
    postgresql-client-common 

# Install Postgre PDO
RUN docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

# update
RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && cd ${PHP_INI_DIR} \
    # - config PHP
    && { \
    echo "upload_max_filesize=128M"; \
    echo "post_max_size=128M"; \
    echo "memory_limit=1G"; \
    echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
