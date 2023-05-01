FROM nginx:1.23.2

ENV TZ=Asia/Tokyo

VOLUME /var/cache/nginx
VOLUME /var/run/

COPY ./laravel /var/www/laravel
COPY ./ecs_dockerimages/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./ecs_dockerimages/nginx/nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/laravel