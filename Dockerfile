FROM php:8.2-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN pecl install xdebug \
&& docker-php-ext-enable xdebug

RUN apt -y install git
RUN apt -y install nano
RUN a2enmod rewrite
# # Install Git
# RUN apk --no-cache add git

# # Set the working directory
# WORKDIR /var/www/html/ 

# # Clone your Git repository