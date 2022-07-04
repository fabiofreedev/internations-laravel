# InterNations Test

The test was developed using **PHP 8.1**, **Laravel 9**, **Pest**, **PHPStan** (Larastan) and **laravel-php-cs-fixer**.


### PHPstan

*PHPStan* has been used as a static analyser.  
The level has been set to 6.
For compatibility reason, it has been installed with the composer packages [nunomaduro/larastan](https://github.com/nunomaduro/larastan).

The config file *phpstan.neon* is in the root folder .

### laravel-php-cs-fixer
The *laravl-php-cs-fixer* rules has been taken from the web to adhere as much as possible to the Laravel standard.  
The config file is `config\fixer.php`.

### Laravel Sail
Laravel [Sail](https://laravel.com/docs/8.x/sail) has been used to set up the docker environment.

Its use is not recommended in production. If I had more time, I would have set up a lighter image.

## Setting up the project

### .env file set up

Create a `.env` file in the root directory and paste the content of the `.env.example` file.

### Composer install

To install the composer dependencies please execute this command:

```shell
docker run --rm \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php81-composer:latest \
    composer install
```

### Container startup

Use
```shell
./vendor/bin/sail build
```

to build the docker container and

```shell
./vendor/bin/sail up -d
```

to start it up.

## Tests

For the tests I used [Pest](https://pestphp.com/) and mostly working with TDD

## Considerations

### Roles
Regarding the roles, I decided to create a dedicated table to store only the admin role associated to an
admin user. 

All other users are considered non-admin as default.

### Groups
Any user can belong to many groups, so I created a many-to-many relationship with a pivot table

### Authentication
To authenticate a user and get an access token for the admin routes, I used Laravel Sanctum.

### Admin users
To check if a user has an admin role I used the role table.

To protect the routes I created a middleware to verify if the user is admin by using isAdmin method in User controller.

## What can be done better

In a bigger project and if I had more time I would use Actions, DTO's and Resources
