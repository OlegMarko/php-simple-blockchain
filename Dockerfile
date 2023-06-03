FROM php:8.2-fpm-alpine

ARG DEBIAN_FRONTEND=noninteractive

ARG USER_ID
ARG GROUP_ID

# amd64
#ENV CPPFLAGS -Ofast -falign-functions=32 -fno-lto -fno-semantic-interposition -mno-vzeroupper -mprefer-vector-width=256 -march=x86-64-v3
# arm64
ENV CPPFLAGS -Ofast -fno-lto -fno-semantic-interposition

ENV LDFLAGS -s

# Install PHP extensions
RUN apk add --update \
		$PHPIZE_DEPS \
		freetype-dev \
		git \
		libjpeg-turbo-dev \
		libpng-dev \
		libxml2-dev \
		libzip-dev \
		openssh-client \
		php-json \
		php-openssl \
		php-pdo \
		php-pdo_mysql \
		php-session \
		php-simplexml \
		php-tokenizer \
		php-xml \
		imagemagick \
		imagemagick-libs \
		imagemagick-dev \
		php-pcntl \
		php-zip \
	&& docker-php-ext-install soap exif bcmath pdo_mysql pcntl \
	&& docker-php-ext-configure gd --with-jpeg --with-freetype \
	&& docker-php-ext-install gd \
	&& docker-php-ext-install zip

RUN printf "\n" | pecl install imagick pcov \
	&& docker-php-ext-enable --ini-name 20-imagick.ini imagick \
	&& docker-php-ext-enable pcov

RUN pecl install -o -f redis \
	&&  rm -rf /tmp/pear \
	&&  docker-php-ext-enable redis

# COPY ./php.ini /usr/local/etc/php/php.ini

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer

RUN mkdir -p /.composer/cache/vcs
RUN chown -R "${USER_ID}:${GROUP_ID}" /.composer/cache/vcs

USER "${USER_ID}:${GROUP_ID}"

# Set working directory
WORKDIR /var/www/app
