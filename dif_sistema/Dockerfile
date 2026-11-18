FROM php:8.2-apache

# Instalar extensiones necesarias para conectar PHP con MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar TODOS los archivos de la carpeta src al servidor web
COPY ./src /var/www/html/

# Ajustar permisos de lectura/escritura
RUN chown -R www-data:www-data /var/www/html