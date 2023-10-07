FROM nginx:1.24-alpine3.17-slim

LABEL maintainer='Iqbal Hikmat Prahara'

COPY nginx.conf /etc/nginx/
COPY app.conf.template /etc/nginx/templates/
COPY docker-entrypoint.sh /

RUN apk add --no-cache --update logrotate curl bash

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

ARG PHP_UPSTREAM_CONTAINER=app
ARG PHP_UPSTREAM_PORT=9000

# Create 'messages' file used from 'logrotate'
RUN touch /var/log/messages

# Copy 'logrotate' config file
COPY logrotate/nginx /etc/logrotate.d/

# Set upstream conf and remove the default conf
RUN echo "upstream octane-server { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" > /etc/nginx/conf.d/upstream.conf

# Clean up
RUN rm -rf /var/cache/apk/*

ADD ./startup.sh /opt/startup.sh
RUN sed -i 's/\r//g' /opt/startup.sh
CMD ["/bin/bash", "/opt/startup.sh"]

EXPOSE 80

HEALTHCHECK --interval=5m --timeout=3s CMD curl --fail ${NGINX_HOST} || exit 1
