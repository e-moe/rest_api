### Update (Feb 4, 2014) ###

* Dependency Injection
* Request and Response objects
* Response status codes and headers fix
* Routing config (see app/config.php)
* Controller trait (for json)
* Location header in response for create, update actions..
* Removed login, access tokens, logs
* ModelsProvider and ControllerFactory

*Live demo*: http://api.labinskiy.org.ua/

*Sources*: https://github.com/e-moe/rest_api/tree/feature/levi9

Requirements:

- Apache (virtualhost, mod_rewright)
- PHP 5.5
- MySQL

Installation:

1. extract files

2. create new or use existing virtual host:

        NameVirtualHost *:80
        <VirtualHost *:80>
            DocumentRoot "path_to_www"
            ServerName your_server_name

            <Directory "path_to_www">
            AllowOverride All
            </Directory>
        </VirtualHost>

3. create new db with tables or use existing:
see dump.sql

4. edit app/configuration.php (DB connection settings)

5. open url: / to read REST API documentation or start directly using it: GET /users/
