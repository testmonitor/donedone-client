# TestMonitor DoneDone Client

[![Latest Stable Version](https://poser.pugx.org/testmonitor/donedone-client/v/stable)](https://packagist.org/packages/testmonitor/donedone-client)
[![CircleCI](https://img.shields.io/circleci/project/github/testmonitor/donedone-client.svg)](https://circleci.com/gh/testmonitor/donedone-client)
[![Travis Build](https://travis-ci.com/testmonitor/donedone-client.svg?branch=master)](https://app.travis-ci.com/github/donedone-client)
[![Code Coverage](https://scrutinizer-ci.com/g/testmonitor/donedone-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/testmonitor/donedone-client/?branch=master)
[![Code Quality](https://scrutinizer-ci.com/g/testmonitor/donedone-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/testmonitor/donedone-client/?branch=master)
[![StyleCI](https://styleci.io/repos/223800227/shield)](https://styleci.io/repos/225837714)
[![License](https://poser.pugx.org/testmonitor/donedone-client/license)](https://packagist.org/packages/testmonitor/donedone-client)

This package provides a very basic, convenient, and unified wrapper for the [DoneDone REST api](https://www.donedone.com/api).

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Tests](#tests)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

To install the client you need to require the package using composer:

	$ composer require testmonitor/donedone-client

Use composer's autoload:

```php
require __DIR__.'/../vendor/autoload.php';
```

You're all set up now!

## Usage

You'll have to instantiate the client using your credentials:

```php
$donedone = new \TestMonitor\DoneDone\Client('email@server.com', 'API token');
```

Next, you can start interacting with DoneDone.

## Examples

Get a list of DoneDone accounts:

```php
$projects = $donedone->accounts();
```

Or creating a task, for example (using account 123 and project 456):

```php
$task = $donedone->createTask(new \TestMonitor\DoneDone\Resources\Task([
    'title' => 'Some task',
    'description' => 'A better description',
    'status' => 1,
    'priority' => 2,
]), 123, 456);
```

## Tests

The package contains integration tests. You can run them using PHPUnit.

    $ vendor/bin/phpunit

## Changelog

Refer to [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Refer to [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

## Credits

* **Thijs Kok** - *Lead developer* - [ThijsKok](https://github.com/thijskok)
* **Stephan Grootveld** - *Developer* - [Stefanius](https://github.com/stefanius)
* **Frank Keulen** - *Developer* - [FrankIsGek](https://github.com/frankisgek)
* **Muriel Nooder** - *Developer* - [ThaNoodle](https://github.com/thanoodle)

## License

The MIT License (MIT). Refer to the [License](LICENSE.md) for more information.
