
<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Documentation 5.7](https://laravel.com/docs/5.7).


Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## Setup

Api was developed in laravel and has some dependencies to be executed.

- [Install Composer](https://getcomposer.org/doc/00-intro.md)
- [Install Laravel](https://laravel.com/docs/5.7#installation)

## Configuration environment

After performing the facilities mentioned above, some settings need to be made.

#### Create Project

Execute the command:

```shell
composer create-project --prefer-dist laravel/laravel recrutei
```

### Server

Laravel provides a server to run on the application. just run:

```shell
php artisan serve
```

for that application, was used local apache server, and created a vhost.
In the machine's host file

```txt
127.0.0.1   recrutei.test
```

In file 000-default.conf of the apache, add the new vhost
Path /etc/apache2/sites-enabled/000-default.conf.

```txt
<VirtualHost recrutei.test:80>
    ServerName recrutei.test

    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/recrutei/public

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

After adding, restart apache:

```shell
service apache restart
```

### Install Passport

Follow the steps in the laravel [Passport documentation](https://laravel.com/docs/5.7/passport). Installation is via composer.

After performing the documentation procedures, the passport will be enabled for use in api.

### Install Swagger Php + Swagger UI

Para essa api, foi utilizado uma biblioteca [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger), 
It is an OpenApi specification for the Laravel project in a facilitated way.

This package is a wrapper of Swagger-php and swagger-ui adapted to work with Laravel 5.

#### Generate Doc Api

To generate the api documentation run the command below:

```shell
php artisan l5-swagger:generate
```

### Environment variable

In the laravel .env file, the following variables were changed:

```shell
APP_URL=http://recrutei.test

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

### Use Api interface

Before starting to use the api interface it is necessary to perform an initial load of the database.
To create a master user and have full privilege to be able to create other users and roles.

run the commands:

```shell
php artisan db:seed --class=UsersTableSeeder

php artisan migrate --seed
```
Finally, when installing the passport, two clients are generated with client_id and secret_id, to be used to request access token.
in the oauth_clients table should contain 2 records. Use client 2 to generate the token.

If you do not have the record, just run the command below, to create a new client.

```shell
php artisan passport:client
```

Ready! Just use the client's credentials when using the api.

### Scopes

manager: All Privileges<br>
list-car: List the cars<br>
create-car: Create a car<br>
update-car: Update a car<br>
delete-car: Delete a car<br>
