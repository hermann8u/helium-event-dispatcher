# Lium event dispatcher component

A strict [PSR-14](https://www.php-fig.org/psr/psr-14/) implementation built with simplicity in mind.

<!--
[![Build Status](https://img.shields.io/travis/com/PHP-DI/PHP-DI/master.svg?style=flat-square)](https://travis-ci.com/PHP-DI/PHP-DI)
[![Latest Version](https://img.shields.io/github/release/PHP-DI/PHP-DI.svg?style=flat-square)](https://packagist.org/packages/PHP-DI/php-di)
[![Total Downloads](https://img.shields.io/packagist/dt/PHP-DI/PHP-DI.svg?style=flat-square)](https://packagist.org/packages/PHP-DI/php-di)
-->

## Why?

The main goal behind this library is to be a strict implementation of the PSR-14.

## Installation

This library is not available on composer yet...

## Usage

Here is a quick example to show you how to use it :

```php
<?php

use App\Event\UserLoggedIn;
use Lium\EventDispatcher\EventDispatcher;
use Lium\EventDispatcher\ListenerProvider\DefaultListenerProvider;

// Listeners definitions
$listenerCalledForEveryEvent = function (object $event) {
    // This listener will match all events because its argument has the scalar type "object"
    echo sprintf('A listener has been called with the event "%s".', get_class($event));
};

$updateUserLastLoginDate = function (UserHasLoggedIn $event) {
    $user = $event->getUser();
    echo sprintf('The user "%s" has logged in.', $user->getUsername());
};

// Initialization
$listenerProvider = new DefaultListenerProvider([
    $listenerCalledForEveryEvent,
    $updateUserLastLoginDate,
]);

$eventDispatcher = new EventDispatcher($listenerProvider);

// Later in your code...
$eventDispatcher->dispatch(new UserHasLoggedIn($user));
```

The previous example will output :
```
A listener has been called with the event "App\Event\UserLoggedIn".
The user "johndoe" has logged in.
```

You can check the [examples directory](./examples) to see more examples.

## Contributing

See the [CONTRIBUTING](./.github/CONTRIBUTING.md) file.

## License

This project is available with the MIT license. For the full copyright, thanks to read the [license file](./LICENSE).
