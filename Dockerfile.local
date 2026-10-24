FROM php:8.4-apache

# Install required PHP extensions and system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    libtidy-dev \
    wget \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql zip intl exif tidy

# INSTALL IONCUBE LOADER
RUN ARCH=$(uname -m) && \
    if [ "$ARCH" = "x86_64" ]; then \
        URL="https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz"; \
    else \
        URL="https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_aarch64.tar.gz"; \
    fi && \
    cd /tmp && \
    wget $URL -O ioncube_loaders.tar.gz && \
    tar -xvzf ioncube_loaders.tar.gz && \
    EXT_DIR=$(php -r "echo ini_get('extension_dir');") && \
    cp ioncube/ioncube_loader_lin_8.4.so $EXT_DIR/ && \
    echo "zend_extension=$EXT_DIR/ioncube_loader_lin_8.4.so" > /usr/local/etc/php/conf.d/00-ioncube.ini && \
    rm -rf /tmp/ioncube*

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy custom Apache configuration
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# FORCE PERMISSIONS (THE NUCLEAR OPTION)
RUN chmod -R 777 /var/www/html && \
    chown -R www-data:www-data /var/www/html



EXPOSE 80
