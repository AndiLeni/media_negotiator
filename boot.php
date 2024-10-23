<?php


if (rex_addon::get('media_manager')->isAvailable()) {
    rex_media_manager::addEffect(rex_effect_negotiator::class);
}

rex_extension::register('MEDIA_MANAGER_INIT', function (rex_extension_point $ep) {
    $mediaManager = $ep->getSubject();
    $type = $ep->getParam('type');
    $effects = $mediaManager->effectsFromType($type);

    foreach ($effects as $effect) {
        if ($effect['effect'] === 'negotiator') {
            // change cache path for negotiator
            $possible_types = rex_server('HTTP_ACCEPT', 'string', '');
            $types = explode(',', $possible_types);
            $possibleFormat = media_negotiator\Helper::getOutputFormat($types);

            $mediaManager->setCachePath($mediaManager->getCachePath() . $possibleFormat . '-');

            return;
        }
    }
});
