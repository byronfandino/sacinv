FROM php:8.2-apache-bullseye

# Instalar dependencias y habilitar extensiones PHP + Apache
RUN apt-get update && apt-get install -y --no-install-recommends \
      git unzip libfreetype6-dev libjpeg62-turbo-dev libpng-dev libpq-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql \
 && a2enmod rewrite \
 && sed -i 's|/var/www/html|/var/www/public|g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|/var/www/html|/var/www/public|g' /etc/apache2/apache2.conf \
 && sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf \
 && git config --global --add safe.directory /var/www \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copiar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Script de inicio
COPY init-apache.sh /usr/local/bin/init-apache.sh
RUN chmod +x /usr/local/bin/init-apache.sh

WORKDIR /var/www

CMD ["/usr/local/bin/init-apache.sh"]
