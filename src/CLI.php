<?php

declare(strict_types=1);

namespace Imgproxy;

use Composer\Script\Event;

class CLI
{
    public static function generate(Event $event)
    {
        $opts = $event->getArguments();
        $base = null;
        $key = null;
        $salt = null;
        $source = null;
        $width = 0;
        $height = 0;
        $advanced = false;
        for ($i = 0; $i < count($opts); $i++) {
            $name = $opts[$i];
            $name = ltrim($name, "-");
            switch ($name) {
                case "base":
                    $base = $opts[++$i];
                    break;
                case "key":
                    $key = $opts[++$i];
                    break;
                case "salt":
                    $salt = $opts[++$i];
                    break;
                case "source":
                    $source = $opts[++$i];
                    break;
                case "width":
                    $width = $opts[++$i];
                    break;
                case "height":
                    $height = $opts[++$i];
                    break;
                case "advanced":
                    $advanced = true;
                    break;
                default:
                    break;
            }
        }
        $builder = new UrlBuilder($base, $key, $salt);
        $url = new Url($builder, $source, $width, $height);
        if ($advanced) {
            $url->useAdvancedMode();
        }
        echo $url->toString() . "\n";
    }
}