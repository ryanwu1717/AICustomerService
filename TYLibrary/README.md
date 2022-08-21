# Slim Framework 3 Skeleton Application

Use this skeleton application for the slim 3 micro-framework with some pre configured dependencies and structures:

- [Twig](https://github.com/twigphp/Twig) as View engine
- [Eloquent](https://laravel.com/docs/5.4/eloquent) as ORM for database work
- [Monolog](https://github.com/monolog/monolog) for logging
- [PHP DI](https://github.com/PHP-DI/PHP-DI) for dependency injection
- [Silly CLI micro-framework](https://github.com/mnapoli/silly) for CLI Commands
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) to load environment configuration from ".env" file
- [dtkahl/php-simple-config](https://github.com/dtkahl/php-simple-config) for simple access to configuration files
- [dtkahl/php-file-cache](https://github.com/dtkahl/php-file-cache) as simple file cache
- [Webpack](https://github.com/webpack/webpack) to bundle assets and build SCSS 
- Controller, Middleware and Factory classes
- Exception handling
- Maintenance mode

If you don't need all of this, you should just use [slimphp/Slim-Skeleton](https://github.com/slimphp/Slim-Skeleton) which I forked and extended with this features.

## Requirements

- PHP >= 7.0
- Composer
- NodeJS (for Webpack)


## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

```
    composer create-project dtkahl/slim-skeleton [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

- Point your virtual host document root to your new application's `public/` directory.
- Ensure `storage/` is web writeable.
- create a copy ".env" of the file ".env.example" an set up your configuration

Install NodeJS dependencies:

```
npm install
```


## Development

for development you can use the PHP built-in webserver:

```
    php -S 0.0.0.0:8080 -t public index.php
```

And let webpack bundle your assets live:

```
    npm run watch
```


## On Production

Make sure to bundle your assets after clone/update your assets on production by running:

```
    npm run build
```
