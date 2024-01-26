FROM php:8.2-fpm

LABEL author="Arnaud ANDRIANOMANANA"
LABEL org.opencontainers.image.source=https://github.com/aandriano931/home-hub
LABEL org.opencontainers.image.description="PHP image for laravel application"

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    ca-certificates \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev \
    libicu-dev \
    gnupg && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl intl
RUN docker-php-ext-configure gd --with-external-gd
RUN docker-php-ext-install gd

# Import Nodesource & install Node
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list

RUN apt-get update && apt-get install nodejs -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Install composer & dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change current user to www
USER www

# Expose port 9000 and 5173 and start php-fpm server
EXPOSE 9000 5173
CMD ["php-fpm"]