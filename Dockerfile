FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
#ARG user
#ARG uid

# Install dependencies some base extensions
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd pdo_pgsql pgsql \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

#COPY docker/supervisord.conf /etc/supervisord.conf

# Create system user to run Composer and Artisan Commands
#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user

# update
RUN set -ex \
    && cd ${PHP_INI_DIR} \
    # - config PHP
    && { \
    echo "memory_limit=1G"; \
    } | tee conf.d/99_overrides.ini \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

#USER $user

COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
