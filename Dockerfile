FROM alpine:latest
LABEL Maintainer="Vincenzo Cardone <info@vcardone.it>"
LABEL Description="Flurga is a web interface for Frigate NVR"
WORKDIR /flurga/public
RUN apk add --no-cache \
  curl \
  nginx \
  php81 \
  php81-ctype \
  php81-curl \
  php81-dom \
  php81-pecl-yaml \
  php81-fpm \
  php81-gd \
  php81-intl \
  php81-mbstring \
  php81-mysqli \
  php81-opcache \
  php81-openssl \
  php81-phar \
  php81-session \
  php81-xml \
  php81-xmlreader \
  supervisor
RUN ln -sf /usr/bin/php81 /usr/bin/php
COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY config/php.ini /etc/php81/conf.d/custom.ini
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chown -R nobody.nobody /flurga/public /run /var/lib/nginx /var/log/nginx /flurga
USER nobody
COPY --chown=nobody /public/ /flurga/public/
COPY --chown=nobody config.yml /flurga/config.yml
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping