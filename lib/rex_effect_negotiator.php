<?php

use media_negotiator\Helper;

class rex_effect_negotiator extends rex_effect_abstract
{

    public function getName()
    {
        return "Negotiate image format";
    }

    public function execute()
    {
        // get image mime types which are accepted by the requesting browser
        $possible_types = rex_server('HTTP_ACCEPT', 'string', '');
        $types = explode(',', $possible_types);

        // check which output type is technically possible
        $possibleFormat = Helper::getOutputFormat($types);


        if ($possibleFormat === "avif") {
            // check if GD support is available, if yes use GD
            if (function_exists('imageavif')) {
                // instantiate image formatter class from media manager
                $re = new rex_effect_image_format();
                // pass current image to formatter
                $re->media = $this->media;
                $re->params['convert_to'] = 'avif';
                $re->execute();
            } else {
                // use Imagick
                $img = $this->media->getSource();
                $this->media->setImage(Helper::imagickConvert($img, "avif"));
                $this->media->setFormat("avif");
                $this->media->setHeader('Content-Type', "avif");
                $this->media->refreshImageDimensions();
            }
        } elseif ($possibleFormat === "webp") {
            // check if GD support is available, if yes use GD
            if (function_exists('imagewebp')) {
                $re = new rex_effect_image_format();
                $re->media = $this->media;
                $re->params['convert_to'] = 'webp';
                $re->execute();
            } else {
                // use Imagick
                $img = $this->media->getSource();
                $this->media->setImage(Helper::imagickConvert($img, "webp"));
                $this->media->setFormat("webp");
                $this->media->setHeader('Content-Type', "webp");
                $this->media->refreshImageDimensions();
            }
        } else {
            // do not change format and deliver original file
        }
    }
}
