FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Copiar el proyecto al contenedor
COPY . /var/www/html/

# Configurar Apache para que use /public como DocumentRoot
WORKDIR /var/www/html
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/|/var/www/html/public|g' /etc/apache2/apache2.conf

# Permisos
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

EXPOSE 80
CMD ["apache2-foreground"]
