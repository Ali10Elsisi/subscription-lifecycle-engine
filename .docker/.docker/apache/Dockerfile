FROM php:8.4-apache
WORKDIR /var/www/html

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    build-essential \
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
    libxml2-dev \
    libonig-dev \
    libxslt-dev \
    zlib1g-dev

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets

# Enable extensions
RUN docker-php-ext-enable mysqli pdo pdo_mysql sockets

# Install other PHP extensions
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/
RUN docker-php-ext-install gd mbstring zip exif pcntl

# Apache and other configurations
RUN mkdir /app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 2024M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size = 2024M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "memory_limit = 2024M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "max_execution_time = 30000" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "max_input_time = 30000" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "max_file_uploads = 1000" >> /usr/local/etc/php/conf.d/uploads.ini
RUN chown -R www-data:www-data /app && a2enmod rewrite

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Custom Aliases for convenience
RUN echo "alias chp='chmod -R 0777 app/ database/ storage/ config/ resources/ tests/'" >> ~/.bashrc
RUN echo "alias autoload='composer dumpautoload'" >> ~/.bashrc
RUN echo "alias pa='php artisan'" >> ~/.bashrc
RUN echo "alias tinker='php artisan tinker'" >> ~/.bashrc
RUN echo "alias optimize='php artisan optimize'" >> ~/.bashrc
RUN echo "alias schema='php artisan schema:dump'" >> ~/.bashrc
RUN echo "alias dump='php artisan db:dump'" >> ~/.bashrc
RUN echo "alias migrate='php artisan migrate'" >> ~/.bashrc
RUN echo "alias smigrate='php artisan migrate --seed'" >> ~/.bashrc
RUN echo "alias seed='php artisan db:seed'" >> ~/.bashrc
RUN echo "alias fresh='php artisan migrate:fresh'" >> ~/.bashrc
RUN echo "alias freshs='php artisan migrate:fresh --seed'" >> ~/.bashrc
RUN echo "alias freshd='php artisan migrate:fresh --schema-path database/schema/backup-schema.sql'" >> ~/.bashrc
RUN echo "alias rmschema='rm -f database/schema/mysql-schema.sql'" >> ~/.bashrc
RUN echo "alias pint='./vendor/bin/pint'" >> ~/.bashrc
RUN echo "alias stan='./vendor/bin/phpstan'" >> ~/.bashrc
RUN echo "alias test='php artisan test'" >> ~/.bashrc
RUN echo "alias prepush='pint && test'" >> ~/.bashrc
