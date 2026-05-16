FROM php:8.1-apache

# Installer les extensions PHP nécessaires (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Activer le module de réécriture d'Apache (mod_rewrite)
RUN a2enmod rewrite

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html/

# Exposer le port 80
EXPOSE 80
