FROM php:8.2-apache

# Instalar dependencias necesarias y extensiones (GD + PostgreSQL)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# Copiar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cambiar DocumentRoot a /var/www/public y permitir .htaccess
RUN sed -i 's|/var/www/html|/var/www/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/public|g' /etc/apache2/apache2.conf \
    && sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Marcar directorio como seguro para Git
RUN git config --global --add safe.directory /var/www

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Carpeta de trabajo
WORKDIR /var/www

# Copiar script de inicio
COPY init-apache.sh /usr/local/bin/init-apache.sh
RUN chmod +x /usr/local/bin/init-apache.sh

CMD ["/usr/local/bin/init-apache.sh"]
