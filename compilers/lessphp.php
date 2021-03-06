<?php 

require "lessphp/lessc.inc.php";
$file = $_GET["file"];

function autoCompileLess($inputFile, $outputFile) {
  // load the cache
  $cacheFile = $inputFile.".cache";

  if (file_exists($cacheFile)) {
    $cache = unserialize(file_get_contents($cacheFile));
  } else {
    $cache = $inputFile;
  }

  $less = new lessc;
  $less->setFormatter("compressed");
  $newCache = $less->cachedCompile($cache);

  if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
    file_put_contents($cacheFile, serialize($newCache));
    file_put_contents($outputFile, $newCache['compiled']);
  }
}

autoCompileLess('../' . $file, '../' . $file . '.css');

header('Content-type: text/css');
readfile('../' . $file . '.css');

?>