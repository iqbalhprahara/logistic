#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#
FROM php:8.2-alpine

LABEL maintainer='Iqbal Hikmat Prahara <iprahara@alphatree-capital.com>'

RUN apk --update add wget \
  shadow\
  curl \
  git \
  build-base \
  libmcrypt-dev \
  libxml2-dev \
  pcre-dev \
  zlib-dev \
  autoconf \
  cyrus-sasl-dev \
  libgsasl-dev \
  oniguruma-dev \
  libressl \
  postgresql-dev \
  supervisor


RUN pecl channel-update pecl.php.net; \
    docker-php-ext-install mbstring pdo pdo_pgsql pgsql pcntl

# Add a non-root user:
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

USER root
#Install ImageMagick:
RUN set -eux; \
    apk add --update --no-cache imagemagick-dev imagemagick; \
    pecl install imagick; \
    docker-php-ext-enable imagick; \
    php -m | grep -q 'imagick'

#Install BCMath package:
RUN docker-php-ext-install bcmath

# Install ZipArchive:
RUN set -eux; \
    apk --update add libzip-dev && \
    docker-php-ext-configure zip; \
    # Install the zip extension
    docker-php-ext-install zip

#
#--------------------------------------------------------------------------
# Optional Supervisord Configuration
#--------------------------------------------------------------------------
#
# Modify the ./supervisor.conf file to match your App's requirements.
# Make sure you rebuild your container with every change.
#

COPY supervisord.conf /etc/supervisor/supervisord.conf

WORKDIR /var/www/html

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]

#
#--------------------------------------------------------------------------
# Optional Software's Installation
#--------------------------------------------------------------------------
#
# If you need to modify this image, feel free to do it right here.
#
    # -- Your awesome modifications go here -- #

#
#--------------------------------------------------------------------------
# Check PHP version
#--------------------------------------------------------------------------
#

RUN php -v | head -n 1 | grep -q "PHP 8.2."

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

# Clean up
RUN rm /var/cache/apk/* \
    && mkdir -p /var/www
