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

            // check if file is cached, if yes return 
            if ($this->isCached($possibleFormat)) {
                return;
            }

            // check if force_imagick enabled or imageavif not available, else use GD
            if (rex_config::get("media_negotiator", "force_imagick", false) || !function_exists('imageavif')) {
                // use Imagick
                $img = $this->media->getSource();
                $this->media->setImage(Helper::imagickConvert($img, "avif"));
                $this->media->setFormat("avif");
                $this->media->setHeader('Content-Type', "avif");
                $this->media->refreshImageDimensions();
            } else {
                // use GD
                // instantiate image formatter class from media manager
                $re = new rex_effect_image_format();
                // pass current image to formatter
                $re->media = $this->media;
                $re->params['convert_to'] = 'avif';
                $re->execute();
            }
        } elseif ($possibleFormat === "webp") {

            if ($this->isCached($possibleFormat)) {
                return;
            }

            if (rex_config::get("media_negotiator", "force_imagick", false) || !function_exists('imagewebp')) {
                // use Imagick
                $img = $this->media->getSource();
                $this->media->setImage(Helper::imagickConvert($img, "webp"));
                $this->media->setFormat("webp");
                $this->media->setHeader('Content-Type', "webp");
                $this->media->refreshImageDimensions();
            } else {
                $re = new rex_effect_image_format();
                $re->media = $this->media;
                $re->params['convert_to'] = 'webp';
                $re->execute();
            }
        } else {
            // do not change format and deliver original file
        }
    }

    private function isCached($targetFormat)
    {
        $cachePath = rex_path::addonCache('media_manager');
        $type = $this->params["mediatyp"];
        $originalFilename = $this->media->getMediaFilename();

        $cacheFile = "{$cachePath}{$targetFormat}-{$type}/{$originalFilename}";

        if (is_file($cacheFile)) {
            return true;
        } else {
            return false;
        }
    }


    public function getParams()
    {
        $effectId = rex_get("type_id", "int", 0);
        $sql = rex_sql::factory();
        $res = $sql->setQuery("select name from " . rex::getTable("media_manager_type") . " where id = ?", [$effectId]);
        $typName = $res->getValue("name");

        return [
            [
                'label' => "Name dieses Effekts",
                'name' => 'mediatyp',
                'type' => 'string',
                'notice' => "Hier bitte den Namen des Mediatyps eintragen. (Sollte korrekt vorausgefüllt sein)",
                'default' => $typName,
            ],
        ];
    }
}
