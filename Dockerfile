FROM php:8.2-apache

RUN a2enmod rewrite
RUN a2dismod mpm_event && a2enmod mpm_prefork

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80