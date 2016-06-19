# OpenBank

OpenBank is a Laravel based web application that you can use to keep track of your BitCoin public keys, your total balance and so forth.
All the data is collected in realtime and will be shown to you on its web interface.

### Screenshot

This is a screenshot of my instance running on a Raspberry Pi.

![Screenshot](/screenshot.png?raw=true)

### Safe

The database only stores the password you're using to access your OpenBank instance and your public keys, **no sensitive data will be leaked even if compromised**.
Moreover if you install it on a Raspberry Pi running on your home network, you won't leak your ip address to a third entity.

### How does it work

All data is updated in realtime using two different API, one job will get the current price every minute using the [bitcoinaverage.com](https//bitcoinaverage.com/) API while your total balance is updated using the [blockonomics.co](https://www.blockonomics.co/) API.

**IMPORTANT NOTE ABOUT XPUB**

If you're adding an **xPub** key with more than 50 addresses bound to it, you'll need to get an API key from blockonomics and put it in your settings panel. Make sure you add **all** your addresses/keys to the wallet watcher service or else it will appear with 0 balance.

### Installation

##### 1. Checkout this git repository to your server.

    git clone https://github.com/evilsocket/openbank.git

##### 2. Install dependencies.

For details about installing composer see [Getcomposer.org](https://getcomposer.org/).

     cd openbank
     composer install

##### 3. Setup environment.

Copy `.env.example` to `.env` and edit to your needs.

##### 4. Fix permissions.

Make sure the `storage` directory is writable for your web server.

##### 5. Initialize database.

	php artisan key:generate
	php artisan migrate
	php artisan db:seed --class=CurrenciesTableSeeder

#####	6. Create the cronjob

Install the needed cronjob:

    */1 * * * * /usr/bin/php /var/www/openbank/www/artisan schedule:run

##### 7. Register.

Open OpenBank in your browser, register your account and enjoy.

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
