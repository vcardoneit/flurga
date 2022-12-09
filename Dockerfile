FROM trafex/php-nginx:latest
CMD ["/bin/sh"]
LABEL Maintainer="Vincenzo Cardone <info@vcardone.it>"
WORKDIR /flurga
RUN rm -rf /var/www/html/*
COPY /public/ /var/www/html/
COPY app.ini /flurga/app.ini