<?php
if (isset($_GET['pack_id'])) {
    require __DIR__ . '/inc/Roundsound.class.php';
    $class = new Roundsound();
    $class->downloadZip($_GET['pack_id']);
    $class->removeInvalidFiles();
} elseif (isset($_GET['random'])) {
    require __DIR__ . '/inc/Roundsound.class.php';
    $class = new Roundsound();
    $class->downloadZip('random');
    $class->removeInvalidFiles();
} else {
    die('Invalid request!');
}
?>