# See https://github.com/docker-library/php/blob/4677ca134fe48d20c820a19becb99198824d78e3/7.0/fpm/Dockerfile
FROM zajca/php:7.2-plain

MAINTAINER Martin Zajic <zajca@zajca.cz>

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 64M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 64M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 512M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 600;" >> /usr/local/etc/php/conf.d/uploads.ini

RUN cp -r /usr/local/etc/php/conf.d /usr/local/etc/conf-noxdebug.d && rm -f /usr/local/etc/conf-noxdebug.d/docker-php-ext-xdebug.ini
RUN echo 'alias phpnx="PHP_INI_SCAN_DIR=/usr/local/etc/conf-noxdebug.d php"' >> ~/.bashrc

#ADMINER
ARG ADMINER_VERSION
RUN echo "ADMINER_VERSION: $ADMINER_VERSION.php"
RUN mkdir -p /var/www/adminer && curl -o /var/www/adminer/adminer.php "https://www.adminer.org/static/download/$ADMINER_VERSION/adminer-$ADMINER_VERSION.php"

RUN cp -r /usr/local/etc/php/conf.d /usr/local/etc/conf-noxdebug.d && rm -f /usr/local/etc/conf-noxdebug.d/docker-php-ext-xdebug.ini
RUN echo 'alias phpnx="PHP_INI_SCAN_DIR=/usr/local/etc/conf-noxdebug.d php"' >> ~/.bashrc

RUN curl -o /usr/local/bin/php-cs-fixer https://cs.sensiolabs.org/download/php-cs-fixer-v2.phar && chmod a+x /usr/local/bin/php-cs-fixer
RUN curl -Lo /usr/local/bin/phpmd http://static.phpmd.org/php/latest/phpmd.phar && chmod a+x /usr/local/bin/phpmd
RUN curl -Lo /usr/local/bin/deptrac http://get.sensiolabs.de/deptrac.phar && chmod a+x /usr/local/bin/deptrac

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer &&\
    composer --version

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN mkdir -p /var/www/symfony/var &&\
    chmod ugo=rwX /var/www/symfony/var

WORKDIR /var/www/symfony
