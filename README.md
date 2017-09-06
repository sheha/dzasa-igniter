**PHONEBOOK app.**

PHP, CodeIgniter3, Bootstrap3, MySql, jQuery(2.2.4), ajax 

Packages: loaded from CDN servers

Environment: PHP7-FPM, NGINX. 

Nginx vhost setup:
````
server {
    listen 80;
    server_name phonebook.dev www.phonebook.dev;

    root /usr/share/nginx/html/phonebook;

    index index.php index.html;

    # set expiration of assets to MAX for caching
    location ~* \.(ico|css|js|gif|jpe?g|png)(\?[0-9]+)?$ {
             expires 1d;
             log_not_found on;
    }

   location / {
                # Check if a file or directory index file exists, else route it to index.php.
                try_files $uri $uri/ /index.php;
   }

   location ~* \.php {

        include fastcgi.conf;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_cache off;
        fastcgi_index index.php;
    }

   location ~ /\.ht {
        deny all;
    }
}

 ````
 
 Database dump provided in _init, together with the initial create script
 Test user: ismar@mail.com;Password1
 
 The app features:
 User management (Register, Login, Forgot pasword mailer, Change password, Logout) 
 Dashboard Screen ( Datagrid populated with Ajax Api calls,No caching) 
 
 
Ismar Sehic, i.sheeha@gmail.com