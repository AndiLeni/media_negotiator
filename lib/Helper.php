<?php

namespace media_negotiator;

use rex;
use rex_version;
use Imagick;

class Helper
{


    public static function getOutputFormat($requestedTypes): string
    {
        $possibleFormat = "";

        // first check webp and set it, can overridden by avif in next step if avif is available
        if (in_array('image/webp', $requestedTypes)) {
            // check if webp output is possible

            if ((function_exists('imagewebp') || class_exists(Imagick::class))) {
                $possibleFormat = "webp";
            }
        }

        // check if avif output is possible
        if (in_array('image/avif', $requestedTypes)) {

            // check if redaxo version >= 5.15.0 (media_manager supports avif from this version upwards, must be true for MM and Imagick)
            // and if either imageavif() is available or Imagick installed
            if (rex_version::compare(rex::getVersion(), '5.15.0', '>=') && (function_exists('imageavif') || class_exists(Imagick::class))) {
                $possibleFormat = "avif";
            }
        }

        // if neither of those methods is available do not convert at all
        return $possibleFormat;
    }

    public static function imagickConvert($gdImage, $targetFormat)
    {
        $imagick = new Imagick();

        $imagick->readImageBlob($gdImage);
        $imagick->setImageFormat($targetFormat);

        $gd = imagecreatefromstring($imagick->getImageBlob());

        return $gd;
    }
}
