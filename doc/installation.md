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

## Download additional libraries (optional)

The Ivory Serializer relies on third libraries in order to not reinvent the wheel...

### Doctrine annotations

The Doctrine annotations library allows you to use the annotations mapping on your classes.

``` bash
$ composer require doctrine/annotations
```

### Symfony event dispatcher

The Symfony event dispatcher library allows you to hook into the (de)-serialization.

``` bash
$ composer require symfony/event-dispatcher
```

### Symfony property info

The Symfony property info library allows you to get a more efficient mapping discovery.
 
``` bash
$ composer require symfony/property-info
```

### Symfony property access

The Symfony property access library allows you to get/set values from your objects.

``` bash
$ composer require symfony/property-access
```

### Symfony yaml

The Symfony yaml library allows you to use the yaml format in your mapping or your (de)-serialization.

``` bash
$ composer require symfony/yaml
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
