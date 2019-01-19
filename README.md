# Mustache View

[![Build Status](https://travis-ci.org/bittyphp/view-mustache.svg?branch=master)](https://travis-ci.org/bittyphp/view-mustache)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/e34be4340dce4a1094fc4b9eb4ef2547)](https://www.codacy.com/app/bittyphp/view-mustache)
[![Total Downloads](https://poser.pugx.org/bittyphp/view-mustache/downloads)](https://packagist.org/packages/bittyphp/view-mustache)
[![License](https://poser.pugx.org/bittyphp/view-mustache/license)](https://packagist.org/packages/bittyphp/view-mustache)

A [Mustache](https://github.com/bobthecow/mustache.php) template view for Bitty.

## Installation

It's best to install using [Composer](https://getcomposer.org/).

```sh
$ composer require bittyphp/view-mustache
```

## Setup

You can use any valid [Mustache Engine](https://github.com/bobthecow/mustache.php/wiki) options, except `loader` which is defined automatically. Another difference is the option `strict_callables` is set to **true** by default.

### Basic Usage

```php
<?php

require(dirname(__DIR__).'/vendor/autoload.php');

use Bitty\Application;
use Bitty\View\Mustache;

$app = new Application();

$app->getContainer()->set('view', function () {
    return new Mustache(dirname(__DIR__).'/templates/', $options);
});

$app->get('/', function () {
    return $this->get('view')->renderResponse('index', ['name' => 'Joe Schmoe']);
});

$app->run();

```

### Multiple Template Paths

If you have templates split across multiple directories, you can pass in an array with the paths to load from.

```php
<?php

use Bitty\View\Mustache;

$mustache = new Mustache(
    [
        'templates/',
        'views/',
    ]
);

$mustache->render('foo');
// Looks for the following templates until it finds one:
// 'templates/foo.mustache'
// 'views/foo.mustache'

```

### Custom Extension

By default, Mustache looks for files with a `.mustache` extension. You can provide an `extension` option to set it to something else.

```php
<?php

use Bitty\View\Mustache;

$mustache = new Mustache(
    'templates/',
    [
        'extension' => 'html',
    ]
);

// Both of these will look for 'templates/foo.html'
$mustache->render('foo');
$mustache->render('foo.html');

```

## Advanced

If you need to do any advanced customization, you can access the Mustache engine directly at any time.

```php
<?php

use Bitty\View\Mustache;

$mustache = new Mustache(...);

/** @var Mustache_Engine */
$engine = $mustache->getEngine();

```
