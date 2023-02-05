<?php

function negotiateFormat($ep)
{

    // rex_media_manager object
    $subject = $ep->getSubject();

    // check if the requested image has the 'negotiator' effect 
    $set_effects = $subject->effectsFromType($subject->getMediaType());
    $set_effects = array_column($set_effects, 'effect');
    $type = $subject->getMediaType();

    // if not, skip
    if (!in_array('negotiator', $set_effects)) {
        return $subject;
    }

    // if yes, set cache path
    if (in_array($type, ['avif'])) {
        $possible_types = rex_server('HTTP_ACCEPT', 'string', '');
        $types = explode(',', $possible_types);


        if (in_array('image/avif', $types)) {
            $subject->setCachePath($subject->getCachePath() . 'avif-');
        } elseif (in_array('image/webp', $types)) {
            $subject->setCachePath($subject->getCachePath() . 'webp-');
        } else {
            $subject->setCachePath($subject->getCachePath() . 'jpg-');
        }
    }

    return $subject;
}


rex_extension::register('MEDIA_MANAGER_BEFORE_SEND', "negotiateFormat");

if (rex_addon::get('media_manager')->isAvailable()) {
    rex_media_manager::addEffect(rex_effect_negotiator::class);
}
