FROM php:8.2-cli

LABEL maintainer='Iqbal Hikmat Prahara <iprahara@alphatree-capital.com>'

# always run apt update when start and after add new source list, then clean up at end.
RUN set -xe; \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
      procps \
      apt-utils \
      gnupg2 \
      git \
      zlib1g-dev \
      libpng-dev \
      libxml2-dev \
      libzip-dev zip unzip \
      supervisor && \
      docker-php-ext-configure zip; \
      # Install the zip extension
      docker-php-ext-install zip && \
      php -m | grep -q 'zip'; \
      docker-php-ext-configure gd --enable-gd;

RUN docker-php-ext-install gd

RUN docker-php-ext-install pcntl

###########################################################################
# PHP SWOOLE:
###########################################################################
RUN pecl install swoole

RUN docker-php-ext-enable swoole

###########################################################################
# bcmath:
###########################################################################

RUN docker-php-ext-install bcmath

###########################################################################
# GETTEXT:
###########################################################################
RUN apt-get install -yqq libicu-dev g++ libpq-dev libssl-dev gettext && \
    docker-php-ext-install gettext;

###########################################################################
# pgsql
###########################################################################
# Install the pgsql extension
RUN docker-php-ext-install pdo pdo_pgsql pgsql

###########################################################################
# pgsql client
###########################################################################
RUN apt-get install -yqq gnupg \
    && . /etc/os-release \
    && echo "deb http://apt.postgresql.org/pub/repos/apt $VERSION_CODENAME-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && curl -sL https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add - \
    && apt-get update -yqq \
    && apt-get install -yqq postgresql-client-12 \
    && apt-get install -yqq \
    && apt-get purge -yqq gnupg;

###########################################################################
# Human Language and Character Encoding Support:
###########################################################################

# Install intl and requirements
RUN apt-get install -yqq zlib1g-dev libicu-dev g++ && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl

###########################################################################
# Image optimizers:
###########################################################################

USER root

RUN apt-get install -yqq jpegoptim optipng pngquant gifsicle

###########################################################################
# ImageMagick:
###########################################################################

USER root

RUN apt-get install -yqq libmagickwand-dev imagemagick && \
    pecl install imagick; \
    docker-php-ext-enable imagick; \
    php -m | grep -q 'imagick'

###########################################################################
# Check PHP version:
###########################################################################

RUN set -xe; php -v | head -n 1 | grep -q "PHP 8.2"

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#
ARG TZ=US/Eastern
ENV TZ ${TZ}

RUN echo "date.timezone=${TZ}" > /usr/local/etc/php/conf.d/timezone.ini

COPY ./app.ini /usr/local/etc/php/conf.d

USER root

# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

# RUN groupmod -o -g ${PGID} www-data && \
#     usermod -o -u ${PUID} -g www-data www-data

RUN set -xe; \
    groupadd -g ${PGID} banana && \
    useradd -l -u ${PUID} -g banana -m banana -G www-data;


###########################################################################
# Composer:
###########################################################################

USER root

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer --2.2

ARG APP_ENV="production"
ENV APP_ENV ${APP_ENV}

RUN echo "Currently running in ${APP_ENV} environment"

###########################################################################
# Node / NVM:
###########################################################################

USER root

RUN apt-get install -yqq nodejs npm

###########################################################################
# Make:
###########################################################################

USER root

RUN apt-get install -yqq make nano


###########################################################################
# OH MY ZSH:
###########################################################################
USER root

RUN apt-get install -y zsh

USER banana

RUN set -xe; \
    cd ~ && \
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

RUN git config --global core.editor "nano"

USER root

RUN chsh -s zsh banana

ENV LC_ALL POSIX

#
#--------------------------------------------------------------------------
# Optional Supervisord Configuration
#--------------------------------------------------------------------------
#
# Modify the ./supervisor.conf file to match your App's requirements.
# Make sure you rebuild your container with every change.
#

COPY startup.sh /opt/startup.sh
COPY supervisord.conf /etc/supervisor/supervisord.conf

CMD ["/bin/bash", "/opt/startup.sh"]

RUN php -v | head -n 1 | grep -q "PHP 8.2."

EXPOSE 9000

USER root
# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

USER banana

WORKDIR /var/www/html

HEALTHCHECK --interval=12s --timeout=12s --start-period=30s CMD php artisan octane:status
