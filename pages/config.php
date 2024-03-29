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

?>

<h3>Verfügbare Funktionen:</h3>

<?php

if (class_exists(Imagick::class)) {
    echo '<p class="text-success bold"><b>Imagick ist installiert</b></p>';

    $imagick = new Imagick();
    $imagickFormats = $imagick->queryFormats();

    if (in_array("WEBP", $imagickFormats)) {
        echo '<p class="text-success bold"><b>Imagick kann WEBP</b></p>';
    } else {
        echo '<p class="text-danger bold"><b>Imagick kann kein WEBP</b></p>';
    }
    if (in_array("AVIF", $imagickFormats)) {
        echo '<p class="text-success bold"><b>Imagick kann AVIF</b></p>';
    } else {
        echo '<p class="text-danger bold"><b>Imagick kann kein AVIF</b></p>';
    }

    $imagickVersion = Imagick::getVersion()["versionString"];
    echo "<p>Imagick Version: " . $imagickVersion . "</p>";
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

if (rex_version::compare(rex::getVersion(), '5.15.0', '>=')) {
    echo '<p class="text-success bold"><b>Redaxo Version neuer als 5.15.0</b></p>';
} else {
    echo '<p class="text-danger bold"><b>Redaxo Version nicht neuer als 5.15.0</b></p>';
}

$canGenerateWebp = Helper::getOutputFormat(['image/webp']) == "webp" ? '<span class="text-success"><b>ja</b></span>' : '<span class="text-danger"><b>nein</b></span>';
$canGenerateAvif = Helper::getOutputFormat(['image/avif']) == "avif" ? '<span class="text-success"><b>ja</b></span>' : '<span class="text-danger"><b>nein</b></span>';

echo "<p>WEBP Ausgabe möglich: {$canGenerateWebp}</p>";
echo "<p>AVIF Ausgabe möglich: {$canGenerateAvif}</p>";



// demo images to see if codecs are installed and output is possible
echo "<h3>Demo Bilder um Konvertierung zu überprüfen:</h3>";

$demo_img = rex_path::addon('media_negotiator', "data/demo.jpg");
$image = imagecreatefromjpeg($demo_img);

if (function_exists('imagewebp')) {
    ob_start();
    imagewebp($image);
    $imageData = ob_get_contents();
    ob_end_clean();
    imagedestroy($image);
    $imageData = base64_encode($imageData);
    echo '<p>imagewebp: <img class="img-thumbnail" src="data:image/webp;base64,' . $imageData . '"></p>';
} else {
    echo '<p>imagewebp: nicht verfügbar</p>';
}

if (function_exists('imageavif')) {
    ob_start();
    imageavif($image);
    $imageData = ob_get_contents();
    ob_end_clean();
    imagedestroy($image);
    $imageData = base64_encode($imageData);
    echo '<p>imageavif: <img class="img-thumbnail" src="data:image/avif;base64,' . $imageData . '"></p>';
} else {
    echo '<p>imageavif: nicht verfügbar</p>';
}

if (class_exists(Imagick::class)) {
    try {
        $image = new Imagick($demo_img);
        $image->setImageFormat('webp');
        $imageData = $image->getImageBlob();
        $imageDataBase64 = base64_encode($imageData);
        echo '<p>Imagick webp: <img class="img-thumbnail" src="data:image/webp;base64,' . $imageDataBase64 . '"></p>';
    } catch (Exception $e) {
        echo "<p>Imagick webp: " . rex_view::error($e->getMessage()) . "</p>";
    }

    try {
        $image = new Imagick($demo_img);
        $image->setImageFormat('avif');
        $imageData = $image->getImageBlob();
        $imageDataBase64 = base64_encode($imageData);
        echo '<p>Imagick avif: <img class="img-thumbnail" src="data:image/avif;base64,' . $imageDataBase64 . '"></p>';
    } catch (Exception $e) {
        echo "<p>Imagick avif: " . rex_view::error($e->getMessage()) . "</p>";
    }
} else {
    echo '<p>Imagick: nicht verfügbar</p>';
}

?>