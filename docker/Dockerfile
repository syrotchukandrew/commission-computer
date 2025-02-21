FROM php:8.3-cli

RUN apt-get update && apt-get install -y git unzip curl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime
RUN "date"

# set up php
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini \
 && echo "display_startup_errors = On" >> /usr/local/etc/php.ini \
 && echo "display_errors = On" >> /usr/local/etc/php/php.ini \
 && echo "date.timezone=\"Europe/Warsaw\"" >> /usr/local/etc/php/php.ini

ENTRYPOINT ["docker-php-entrypoint"]
CMD ["php", "-a"]
