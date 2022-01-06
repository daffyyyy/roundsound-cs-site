<?php
if (empty($_FILES)) {
    die('No files!');
}
$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

if (!in_array($extension, ['mp3'])) {
    die('Invalid file type!');
}

if ($_FILES['file']["size"] > 8300000) {
    die('File size is too big!');
}

require __DIR__ . '/inc/Roundsound.class.php';
$class = new Roundsound();
$uploadDir = 'uploads';
$tmpFile = $_FILES['file']['tmp_name'];
$filename = $uploadDir.'/'. uniqid('roundsound_') . '.' . $extension;
$tempName = $uploadDir.'/'.uniqid('tmp_') . '.' . $extension;

$time = $class->getTimes($_POST['time']);
shell_exec('ffmpeg -ss ' . $time['from'] . ' -t ' . $time['to'] . ' -i ' . $tmpFile . ' -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame ' . $tempName);
sleep(0.2);
if (filesize($tempName) <= 100 * 1024) {
    unlink($tempName);
    die('File is too small!');
}
shell_exec('ffmpeg -i ' . $tempName . ' -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame -af "afade=t=in:st=0:d=3,afade=t=out:st=' . ($time['to'] - 3) . ':d=3" ' . $filename);
sleep(0.1);
unlink($tempName);

// move_uploaded_file($tmpFile, $filename);

$title = (isset($_POST['title']) && strlen($_POST['title']) >= 8) ? $_POST['title'] : 'Brak tytułu';
$class->insertToDb(['title' => $title, 'file' => $filename, 'pack_id' => $_POST['pack_id']]);
?>
