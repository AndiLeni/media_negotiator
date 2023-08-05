<?php

$func = rex_post("func", "string", null);

if ($func == "replaceMM") {

    $originalFilePath = rex_path::addon("media_manager", "lib/media_manager.php");
    $originalSource = file($originalFilePath);
    $newFunctionCode = rex_file::get(rex_path::addon("media_negotiator", "/tpl/getCacheFilename.php"));


    // Create a ReflectionClass instance for the given class name
    $reflectionClass = new ReflectionClass("rex_media_manager");
    $reflectionMethod = $reflectionClass->getMethod('getCacheFilename');

    // get start and end lines of getCacheFilename() function
    $startLine = $reflectionMethod->getStartLine();
    $endLine = $reflectionMethod->getEndLine();

    $beforeFunc = implode("", array_slice($originalSource, 0, $startLine - 1));
    $afterFunc = implode("", array_slice($originalSource, $endLine));


    $newSource = $beforeFunc . $newFunctionCode . "\r\n" . $afterFunc;

    rex_file::put($originalFilePath, $newSource);


    echo rex_view::success("Datei erfolgreich geändert.");
}

?>

<h1>Setup:</h1>

<p>
    Die Datei <code>/redaxo/src/addons/media_manager/lib/media_manager.php</code> muss angepasst werden.
</p>
<p>
    Dies kann <a href="#manuell">manuell</a> gemacht werden, oder man kann die Datei <a href="#automatisch">automatisch</a> ersetzen lassen.
</p>



<h2 id="manuell">Manuell</h2>
<p>
    Bitte manuell die Datei öffnen und die Funktion <code>getCacheFilename()</code> in Folgendes ändern:
</p>
<pre>
public function getCacheFilename()
{
    assert(null !== $this->cachePath);
    assert(null !== $this->type);

    $set_effects = $this->effectsFromType($this->getMediaType());
    $set_effects = array_column($set_effects, 'effect');

    if (!in_array('negotiator', $set_effects)) {
        // do not change cache path
        return $this->cachePath . $this->type . '/' . $this->originalFilename;
    } else {
        // change cache path for negotiator
        $possible_types = rex_server('HTTP_ACCEPT', 'string', '');
        $types = explode(',', $possible_types);
        $possibleFormat = media_negotiator\Helper::getOutputFormat($types);

        return $this->cachePath . $possibleFormat . "-" . $this->type . '/' . $this->originalFilename;
    }
}
</pre>


<h2 id="automatisch">Automatisch</h2>
<p>
    Alternativ kann dies auch mit einem Klick auf den folgenden Button gemacht werden (dies ersetzt die Funktion getCacheFilename() in der media_manager.php Datei durch eine Version mit der benötigten Anpassung).
</p>

<form method="post">
    <input type="hidden" name="func" value="replaceMM">
    <button class="btn btn-primary" type="submit">Ersetzen</button>
</form>

<p>
    <?= rex_view::info("Diese Anpassung muss nach jedem Update des Media Managers oder Redaxo Updates erneut gemacht werden, da die Datei in ihren ursprünglichen Zustand versetzt wird beim updaten.") ?>
</p>