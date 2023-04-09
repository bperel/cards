FROM php:8-cli-alpine
RUN apk add freetype-dev \
            libjpeg-turbo-dev \
            libpng-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) gd

WORKDIR /home

ENTRYPOINT ["/bin/sh"]
