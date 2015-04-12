<?php
namespace Corley\Deb\Describe;

use Symfony\Component\Yaml\Parser;

class YmlParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser();
    }

    public function testSimpleParse()
    {
        $yml = $this->parser->parse(<<<EOF
output_path: "/path/to/out"
mount:
 - { "src": "./here", "dest": "/mnt/out" }
 - { "src": "./here2", "dest": "/mnt/out2" }
 - { "src": "./herer3", "dest": "/mnt/out3" }
control:
  package_name: my-package-name
  version: 0.0.1
  description: |
    Here is a long description
  depends:
    - php5
    - php5-cli
EOF
        );

        $this->assertInternalType("array", $yml);
        $this->assertEquals("/path/to/out", $yml["output_path"]);
        $this->assertInternalType("array", $yml["control"]["depends"]);
        $this->assertCount(2, $yml["control"]["depends"]);
    }
}
