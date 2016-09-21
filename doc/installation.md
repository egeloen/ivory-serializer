# Installation

To install the Ivory Serializer library, you will need [Composer](http://getcomposer.org).  It's a PHP 5.3+ dependency 
manager which allows you to declare the dependent libraries your project needs and it will install & autoload them for 
you.

## Set up Composer

Composer comes with a simple phar file. To easily access it from anywhere on your system, you can execute:

``` bash
$ curl -s https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

## Download the library

Require the library in your `composer.json` file:

``` bash
$ composer require egeloen/serializer
```

## Autoload

So easy, you just have to require the generated autoload file and you are already ready to play:

``` php
<?php

require __DIR__.'/vendor/autoload.php';

use Ivory\Serializer\Serializer;

// ...
```

The Ivory Serializer library follows the [PSR-4 Standard](http://www.php-fig.org/psr/psr-4/). 
If you prefer install it manually, it can be autoload by any convenient autoloader.
