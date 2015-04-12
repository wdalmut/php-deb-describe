<?php

namespace Corley\Deb\Describe;

class KeyFilter
{
    public function filter($name)
    {
        $name = str_replace("_", "-", $name);
        $name = preg_replace_callback("/-\w/i", function($val) {
            if (count($val)) {
                return strtoupper($val[0]);
            }
        }, $name);
        return ucfirst($name);
    }
}
