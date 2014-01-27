*Live demo*: http://api.labinskiy.org.ua/

*Sources*: https://github.com/e-moe/rest_api

Requirements:

- Apache (virtualhost, mod_rewright)
- PHP 5.5
- MySQL

Installation:

* extract files

* create new or use existing virtual host:

    NameVirtualHost *:80
    <VirtualHost *:80>
        DocumentRoot "path_to_www"
        ServerName your_server_name

        <Directory "path_to_www">
          AllowOverride All
        </Directory>
    </VirtualHost>

* create new db with tables or use existing:
see dump.sql

* edit app/configuration.php (DB connection settings)

* open url: / to read REST API documentation or start directly using it: GET /users/