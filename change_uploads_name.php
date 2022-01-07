<?php

$files = glob('uploads/*.mp3');

foreach ($files as $file) {
    echo "INSERT INTO `files` (`file`, `pack_id`) VALUES('".$file."', 'b17014738e5706574ff927958491ca01');" . "<br>";
}

?>