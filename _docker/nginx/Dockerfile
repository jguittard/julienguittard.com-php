FROM nginx:latest

MAINTAINER Julien Guittard <julien.guittard@me.com>

COPY nginx.conf /etc/nginx/
COPY fastcgi.conf /etc/nginx/
COPY jgdotcom.conf /etc/nginx/sites-available/

RUN mkdir /etc/nginx/certs
RUN mkdir -p /etc/ssl/ca/certs

COPY ssl/ssl-params.conf /etc/nginx/snippets/
COPY ssl/openssl.cnf /etc/ssl/ca/
COPY ssl/passphrase.txt /etc/ssl/ca/
COPY ssl/certs/*.pem /etc/ssl/ca/certs/

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN apt-get update && apt-get install -y openssl

RUN usermod -u 1000 www-data

COPY entrypoint.sh /opt/entrypoint.sh
RUN chmod a+x /opt/entrypoint.sh

ENTRYPOINT ["/opt/entrypoint.sh"]
CMD ["nginx"]