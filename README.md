# Describe your Deb packages

Just a simple wrapper around the package
[`wdalmut/php-deb-packager`](https://github.com/wdalmut/php-deb-packager)

 * master [![Build Status](https://travis-ci.org/wdalmut/php-deb-describe.svg?branch=master)](https://travis-ci.org/wdalmut/php-deb-describe)

## Describe a `deb` package with Yaml files

```yml
output_path: /mnt/out
mount:
  - {src: "/first", dest: "/somewhere"}
  - {src: "/src", dest: "/usr/shara/mysw"}
control:
  package: my-package-name
  version: 0.0.1
  depends: php5, php5-cli, php5-curl
  maintainer: Walter Dal Mut [an-email@email.tld]
  provides: something, something-else
  replaces: first-package, second-package
  suggests: php5-mcrypt, php5-xsl
  pre_depends: build-essentials, libc6
  architecture: all
  section: web
```

## Use with composer

Just require it!

```
composer require wdalmut/php-deb-describe:dev-master
```

And use it!

```
./vendor/bin/pdpkg package your.yml
```

## Use it as `phar` package

You can create your `phar` package with [clue/phar-composer](https://github.com/clue/phar-composer)

```sh
phar-composer.phar build wdalmut/php-deb-describe:dev-master
```

## Use the library directly

Just prepare a simple `compile.php` file

```php
<?php
use Symfony\Component\Yaml\Parser;
use wdm\debian\Packager;
use wdm\debian\control\StandardFile;

$parser = new Parser();
$packager = new Packager();
$packager->setControl(new StandardFile());

$describer = new Describer($parser, $packager);
echo $describer->compose(file_get_contents("/path/to/file.yml"));
```

And run it!

```sh
$(php compile.php)
```

Now you have your `.deb` package!
