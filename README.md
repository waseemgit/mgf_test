Pre Requisites
==============
PHP 5.3 and above with Rewrite Engine ON.


Notes
=====

-In case you need database import mgf_test.sql file in database name called mgf_test. But we dont need database in this test I assume.
-Go to includes/config.php to change database settings.Though database is not required in this test but I made model for users in case we have local access for database.
I made database to get field names for three users dynamically from tables. For that you have to change code in Application/Controllers/users.php line 413,416,419 
from "$this->" to "$objUsersModel->" but before that you have to initialize it like $objUsersModel = new users_model().

Assuming we have set virtual host set like following

<VirtualHost *:80>
     ServerName mgf-test.localhost
     DocumentRoot /var/www/html/mgf_test
     SetEnv APPLICATION_ENV "DEV_WASEEM"
     ErrorLog /var/log/apache2/mgf_test.localhost-error_log
     <Directory /var/www/html/mgf_test>
         DirectoryIndex index.php
         AllowOverride All
     </Directory>
</VirtualHost>


-Run it like this http://mgf-test.localhost/




