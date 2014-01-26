*Live demo*: http://api.labinskiy.org.ua/

Requirements:

1) Apache (virtualhost, mod_rewright)
2) PHP
3) MySQL

Installation:

1) extract files

2) create new or use existing virtual host:

    NameVirtualHost *:80
    <VirtualHost *:80>
        DocumentRoot "path_to_www"
        ServerName your_server_name

        <Directory "path_to_www">
          AllowOverride All
        </Directory>
    </VirtualHost>

3) create new db with tables or use existing:
see dump.sql

4) edit app/configuration.php (DB connection settings)

5) open url: / to read REST API documentation or start directly using it: GET /users/