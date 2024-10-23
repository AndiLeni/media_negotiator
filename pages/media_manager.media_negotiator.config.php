<?php

use media_negotiator\Helper;

$form = rex_config_form::factory("media_negotiator");


$field = $form->addRadioField('force_imagick');
$field->setLabel("Imagick erzwingen auch wenn GD Funktionen vorhanden");
$field->addOption('Ja', true);
$field->addOption('Nein', false);

$field = $form->addRadioField('disable_avif');
$field->setLabel("AVIF deaktivieren.");
$field->setNotice("Wenn der Server über keinen AVIF Codec verfügt kann die AVIF Generierung hier deaktiviert werden.");
$field->addOption('Ja', true);
$field->addOption('Nein', false);


$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', "Einstellungen", false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
