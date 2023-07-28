<?php

use media_negotiator\Helper;

$form = rex_config_form::factory("media_negotiator");


$field = $form->addRadioField('force_imagick');
$field->setLabel("Imagick erzwingen auch wenn GD Funktionen vorhanden");
$field->addOption('Ja', true);
$field->addOption('Nein', false);


$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', "Einstellungen", false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');

?>

<h3>Verfügbare Funktionen:</h3>

<?php

if (class_exists(Imagick::class)) {
    echo '<p class="text-success bold"><b>Imagick ist installiert</b></p>';
} else {
    echo '<p class="text-danger bold"><b>Imagick ist nicht installiert</b></p>';
}

if (function_exists('imageavif')) {
    echo '<p class="text-success bold"><b>imageavif ist verfügbar</b></p>';
} else {
    echo '<p class="text-danger bold"><b>imageavif ist nicht verfügbar</b></p>';
}

if (function_exists('imagewebp')) {
    echo '<p class="text-success bold"><b>imagewebp ist verfügbar</b></p>';
} else {
    echo '<p class="text-danger bold"><b>imagewebp ist nicht verfügbar</b></p>';
}

$canGenerateWebp = Helper::getOutputFormat(['image/webp']) == "webp" ? '<span class="text-success"><b>ja</b></span>' : '<span class="text-danger"><b>nein</b></span>';
$canGenerateAvif = Helper::getOutputFormat(['image/avif']) == "avif" ? '<span class="text-success"><b>ja</b></span>' : '<span class="text-danger"><b>nein</b></span>';

echo "<p>WEBP Ausgabe möglich: {$canGenerateWebp}</p>";
echo "<p>AVIF Ausgabe möglich: {$canGenerateAvif}</p>";


?>