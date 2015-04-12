<?php
namespace Corley\Deb\Describe;

use wdm\debian\Packager;
use Symfony\Component\Yaml\Parser;
use wdm\debian\control\StandardFile;
use org\bovigo\vfs\vfsStream;

class DescriberTest extends \PHPUnit_Framework_TestCase
{
    private $root;
    private $object;

    public function setUp()
    {
        $parser = new Parser();

        $this->root = vfsStream::setup('root');

        $packager = $this->getMockBuilder("wdm\\debian\\Packager")->setMethods(["getOutputPath"])->getMock();
        $packager->method("getOutputPath")->willReturn(vfsStream::url('root/tmp'));
        $packager->setControl(new StandardFile());

        $this->object = new Describer($parser, $packager);
    }

    public function testPrepareIt()
    {
        $yml = <<<EOF
output_path: /tmp/prepare-me
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
EOF
        ;

        $this->assertEquals("dpkg -b vfs://root/tmp tmp.deb", $this->object->compose($yml));

        $this->assertTrue($this->root->hasChild('tmp/DEBIAN/control'));
        $content = file_get_contents(vfsStream::url("root/tmp/DEBIAN/control"));

        $this->assertEquals(<<<EOF
Package: my-package-name
Version: 0.0.1
Section: web
Priority: optional
Architecture: all
Essential: no
Depends: php5, php5-cli, php5-curl
Pre-Depends: build-essentials, libc6
Suggests: php5-mcrypt, php5-xsl
Installed-Size: 1024
Maintainer: Walter Dal Mut [an-email@email.tld]
Replaces: first-package, second-package
Provides: something, something-else
Description: Your description

EOF
        , $content);
    }
}
