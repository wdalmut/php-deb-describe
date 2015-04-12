<?php

namespace spec\Corley\Deb\Describe;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Yaml\Parser;
use wdm\debian\Packager;
use wdm\debian\control\StandardFile;

class DescriberSpec extends ObjectBehavior
{
    function let(Parser $parser, Packager $packager)
    {
        $this->beConstructedWith($parser, $packager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Corley\Deb\Describe\Describer');
    }

    function it_should_compose_the_deb(Parser $parser, Packager $packager, StandardFile $control)
    {
        $packager->run()->willReturn($packager);
        $packager->build()->willReturn("dpkg -b something.deb");
        $packager->getControl()->willReturn($control);

        $packager->setOutputPath("/path/to/out")->shouldBeCalledTimes(1);
        $packager->mount("/here", "/mnt/one")->shouldBeCalledTimes(1);
        $packager->mount("/here2", "/mnt/two")->shouldBeCalledTimes(1);

        $control->offsetSet("Package-Name", "my-package-name")->shouldBeCalledTimes(1);
        $control->offsetSet("Version", "0.0.1")->shouldBeCalledTimes(1);

        $yml = [
            "output_path" => "/path/to/out",
            "control" => [
                "package_name" => "my-package-name",
                "version" => "0.0.1",
            ],
            "mount" => [
                ["src" => "/here", "dest" => "/mnt/one"],
                ["src" => "/here2", "dest" => "/mnt/two"],
            ],
        ];

        $parser->parse(Argument::Any())->willReturn($yml);
        $this->compose("parser is mocked out")->shouldReturn("dpkg -b something.deb");
    }
}
