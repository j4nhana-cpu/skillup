FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php8.1 \
    php8.1-mysql \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-curl \
    libapache2-mod-php8.1 \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]