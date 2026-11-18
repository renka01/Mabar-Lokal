FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN { \
    echo "upload_max_filesize=8M"; \
    echo "post_max_size=10M"; \
    echo "date.timezone=Asia/Jakarta"; \
    echo "display_errors=Off"; \
    echo "log_errors=On"; \
    echo "session.cookie_httponly=1"; \
} > /usr/local/etc/php/conf.d/mabarlokal.ini

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

COPY . .

RUN mkdir -p /var/www/html/asset/uploads/avatars \
             /var/www/html/asset/uploads/banners \
             /var/www/html/asset/uploads/posters \
    && chown -R www-data:www-data /var/www/html/asset/uploads \
    && chmod -R 775 /var/www/html/asset/uploads

RUN { \
    echo '<Directory /var/www/html>'; \
    echo '    Options -Indexes +FollowSymLinks'; \
    echo '    AllowOverride All'; \
    echo '    Require all granted'; \
    echo '</Directory>'; \
    echo '<FilesMatch "^\.(env|git|htaccess|dockerignore)">'; \
    echo '    Require all denied'; \
    echo '</FilesMatch>'; \
    echo '<FilesMatch "^(composer\.(json|lock)|Dockerfile)$">'; \
    echo '    Require all denied'; \
    echo '</FilesMatch>'; \
} > /etc/apache2/conf-available/mabarlokal.conf \
    && a2enconf mabarlokal

EXPOSE 10000
CMD ["sh", "-c", "PORT_NUM=${PORT:-10000}; sed -i \"s/Listen 80/Listen ${PORT_NUM}/\" /etc/apache2/ports.conf && sed -i \"s/:80>/:${PORT_NUM}>/\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"]
