<?php

namespace Corley\Deb\Describe;

use wdm\debian\Packager;
use Symfony\Component\Yaml\Parser;
use Corley\Deb\Describe\KeyFilter;

class Describer
{
    private $parser;
    private $packager;
    private $keyFilter;

    public function __construct(Parser $parser, Packager $packager)
    {
        $this->parser = $parser;
        $this->packager = $packager;

        $this->keyFilter = new KeyFilter();
    }

    public function setKeyFilter($keyFilter)
    {
        $this->keyFilter = $keyFilter;
    }

    public function compose($yml)
    {
        $yml = $this->parser->parse($yml);

        foreach ($yml as $key => $value) {
            switch ($key) {
            case "control":
                foreach ($value as $k => $v) {
                    $method = $this->keyFilter->filter($k);
                    $this->packager->getControl()[$method] = $v;
                }
                break;
            case "mount":
                foreach ($value as $line) {
                    $this->packager->mount($line["src"], $line["dest"]);
                }
                break;
            default:
                $method = "set" . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                call_user_func([$this->packager, $method], $value);
            }
        }

        return $this->packager->run()->build();
    }
}
