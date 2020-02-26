WeblabelApiBundle
============

[![Build Status](https://travis-ci.org/weblabel-tech/WeblabelApiBundle.svg?branch=master)](https://travis-ci.org/weblabel-tech/WeblabelApiBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/weblabel-tech/WeblabelApiBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/weblabel-tech/WeblabelApiBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/weblabel-tech/WeblabelApiBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/weblabel-tech/WeblabelApiBundle/?branch=master)

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require weblabel/api-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require weblabel/api-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Weblabel\ApiBundle\WeblabelApiBundle::class => ['all' => true],
];
```
