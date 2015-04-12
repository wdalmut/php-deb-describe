# Describe your Deb packages

Just a simple wrapper around the package
[`wdalmut/php-deb-packager`](https://github.com/wdalmut/php-deb-packager)

## Describe a `deb` package with Yaml files

```yml
output_path: /mnt/out
mount:
  - {src: "/first", dest: "/somewhere"}
  - {src: "/src", dest: "/usr/shara/mysw"}
control:
  package_name: my-package-name
  version: 0.0.1
  depends:
    - php5
    - php5-cli
    - php5-curl
  maintainer: Walter Dal Mut [an-email@email.tld]
  provides: something, something-else
  replaces: first-package, second-package
  suggests: php5-mcrypt, php5-xsl
  pre_depends: build-essentials, libc6
  architecture: all
  section: web
```

## Use the library

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


