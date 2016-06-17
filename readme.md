# OpenBank

OpenBank is a Laravel based web application that you can use to keep track of your BitCoin public keys, their balances
and so forth.
All the data is collected in realtime.

### Screenshot

This is a screenshot of my instance running on a Raspberry Pi.

![Screenshot](/screenshot.png?raw=true)

### Crontab

To install the needed cronjob:

    */1 * * * * /usr/bin/php /var/www/openbank/www/artisan schedule:run

### Sample NGINX Configuration

    server {
      listen       80;
      server_name  ~^(\w*)\.?openbank\.io$;

      root /var/www/openbank.io/www/public;
      access_log /var/www/openbank.io/access.log;
      error_log /var/www/openbank.io/error.log;

      index index.php index.html index.htm;

      location / {
        try_files $uri $uri/ /index.php$is_args$args;
      }


      location ~ \.php$
      {
        try_files $uri /index.php =404;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_script_name;
        include /etc/nginx/fastcgi_params;
      }
    }

### License

This software is released under the GNU 3 license.
Copyleft of Simone 'evilsocket' Margaritelli
https://www.evilsocket.net    
