<?php

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

        // instantiate image formatter class from media manager
        $re = new rex_effect_image_format();
        // pass current image to formatter
        $re->media = $this->media;

        // set convert_to extension 
        if (function_exists('imageavif') && in_array('image/avif', $types)) {
            $re->params['convert_to'] = 'avif';
            $re->execute();
        } elseif (function_exists('imagewebp') && in_array('image/webp', $types)) {
            $re->params['convert_to'] = 'webp';
            $re->execute();
        } else {
            // do not change format and deliver original file
        }
    }
}
