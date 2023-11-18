FROM php:8.2-cli-alpine3.18 as base

LABEL maintainer="Iqbal Hikmat Prahara"

ARG WWWGROUP
ARG WWWUSER

# Set System Default Timezone
ENV TZ='Asia/Jakarta'

# Install required package
RUN apk --update --no-cache add supervisor tzdata

# Add php extension installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

# Install php extensions
RUN install-php-extensions opcache \
    swoole \
    pcntl \
    gd \
    imagick \
    bcmath \
    pgsql \
    pdo_pgsql \
    intl \
    zip \
    @composer

# Configure php.ini
COPY ./app.ini ./opcache.ini /usr/local/etc/php/conf.d/

# Configure Supervisor
COPY supervisord.d /etc/supervisor/supervisord.d
COPY supervisord.conf /etc/supervisord.conf

# Clean up
RUN rm -rf /var/cache/apk/* /usr/local/bin/install-php-extensions

###########################
#       DEVELOPMENT       #
###########################
FROM base as development

RUN apk --update --no-cache add shadow \
    git \
    zsh \
    nano \
    npm \
    make

# Configure non-root user for development
RUN set -xe; \
    addgroup -g ${WWWGROUP} -S developer; \
    adduser -u ${WWWUSER} -S -G developer developer -D -s zsh

# Configure Oh MyZsh & Git
USER developer

RUN set -xe; \
    cd ~ && \
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

RUN git config --global core.editor "nano"

USER root

# Clean up
RUN apk del shadow
RUN rm -rf /var/cache/apk/*

# remove octane from development
RUN rm -rf /etc/supervisor/supervisord.d/octane.conf

USER developer

WORKDIR /var/www/html

###########################
#       PRODUCTION        #
###########################
FROM base as production

RUN apk --update --no-cache add shadow

RUN set -eux; \
    groupmod --gid ${WWWGROUP} www-data; \
    usermod --uid ${WWWUSER} --gid ${WWWGROUP} www-data

# Clean up
RUN apk del shadow
RUN rm -rf /var/cache/apk/*

USER www-data

WORKDIR /var/www/html

HEALTHCHECK --interval=12s --timeout=12s --start-period=30s CMD php artisan octane:status
