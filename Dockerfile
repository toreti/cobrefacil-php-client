FROM php:8.2.8-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install Xdebug
RUN pecl install xdebug-3.2.2
RUN docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Create a symbolic link
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set user to run commands
USER $user

# Set working directory
WORKDIR /var/www
