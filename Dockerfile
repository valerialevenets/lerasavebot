FROM php:8.3-cli

RUN apt-get update \
 && apt-get install -y git yt-dlp \
 && curl -sS https://getcomposer.org/installer \
      | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/bot
#CMD ["php", "./NewBot.php"]