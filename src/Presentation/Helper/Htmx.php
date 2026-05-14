<?php

namespace DeinBrett\Presentation\Helper;

class Htmx
{
    public static function isHtmx(): bool
    {
        return isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';
    }
}
