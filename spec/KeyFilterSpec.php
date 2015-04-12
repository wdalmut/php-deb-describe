<?php

namespace spec\Corley\Deb\Describe;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KeyFilterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Corley\Deb\Describe\KeyFilter');
    }

    function it_should_convert_simple_param()
    {
        $this->filter("depends")->shouldReturn("Depends");
    }

    function it_should_convert_underscores()
    {
        $this->filter("pre_depends")->shouldReturn("Pre-Depends");
    }

    function it_should_convert_multiple_underscores()
    {
        $this->filter("pre_pre_depends")->shouldReturn("Pre-Pre-Depends");
    }
}
