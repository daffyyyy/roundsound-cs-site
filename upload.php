<?php
$yt = isset($_GET['yt']);
if (!$yt)
{
    if (empty($_FILES)) {
        die('Error: No files!');
    }
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if (!in_array($extension, ['mp3'])) {
        die('Error: Invalid file type!');
    }

    if ($_FILES['file']["size"] > 8300000) {
        die('Error: File size is too big!');
    }

    require __DIR__ . '/inc/Roundsound.class.php';
    $class = new Roundsound();
    $uploadDir = 'uploads';
    $tmpFile = $_FILES['file']['tmp_name'];
    $filename = $uploadDir.'/'. uniqid('roundsound_') . '.' . $extension;
    $tempName = $uploadDir.'/'.uniqid('tmp_') . '.' . $extension;

    $time = $class->getTimes($_POST['time']);
    if ($time['to'] > 60) {
        die('Error: Too long duration!');
    }
    shell_exec('ffmpeg -ss ' . $time['from'] . ' -t ' . $time['to'] . ' -i ' . $tmpFile . ' -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame ' . $tempName);
    sleep(0.2);
    if (filesize($tempName) <= 100 * 1024) {
        unlink($tempName);
        die('Error: File is too small!');
    }
    shell_exec('ffmpeg -i ' . $tempName . ' -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame -af "afade=t=in:st=0:d=3,afade=t=out:st=' . ($time['to'] - 3) . ':d=3" ' . $filename);
    sleep(0.1);
    unlink($tempName);

    // move_uploaded_file($tmpFile, $filename);

    $title = (isset($_POST['title']) && strlen($_POST['title']) >= 8) ? $_POST['title'] : 'Brak tytułu';
    $class->insertToDb(['title' => $title, 'file' => $filename, 'pack_id' => $_POST['pack_id']]);
} else {
    require __DIR__ . '/inc/Roundsound.class.php';
    $class = new Roundsound();

    $uploadDir = 'uploads';
    $extension = 'mp3';

    $postArray = $_POST;
    if (strlen($postArray['youtube-url'][0]) < 10 || strpos($postArray['youtube-url'][0], 'youtube') === false) {
        die('Error: Invalid youtube url!');
    }
    if (strlen($postArray['youtube-time'][0]) < 11) {
        die('Error: Duration is too short!');
    }

    for ($i = 0; $i < count($postArray['youtube-url']); $i++) {
        try{
            $filename = $uploadDir.'/'. uniqid('roundsound_') . '.' . $extension;
            $tempName = $uploadDir.'/'.uniqid('tmp_') . '.' . $extension;
            $tmpName = 'tmp/'.uniqid('tmp_') . '.' . $extension;
        
            $time = $class->getTimes($postArray['youtube-time'][$i]);
            if ($time['to'] > 60) {
                die('Error: Too long duration!');
            }
            shell_exec('youtube-dl --prefer-ffmpeg -x --audio-format mp3 -o "' . $tmpName . '" ' . $postArray['youtube-url'][$i]);
            sleep(0.2);
            shell_exec('ffmpeg -ss ' . $time['from'] . ' -t ' . $time['to'] . ' -i ' . $tmpName . ' -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame ' . $tempName);
            sleep(0.2);
            if (filesize($tempName) <= 100 * 1024) {
                unlink($tempName);
                die('Error: File is too small!');
            }
            shell_exec('ffmpeg -i ' . $tempName . ' -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame -af "afade=t=in:st=0:d=3,afade=t=out:st=' . ($time['to'] - 3) . ':d=3" ' . $filename);
            sleep(0.1);
            unlink($tmpName);
            unlink($tempName);
            $title = (isset($postArray['youtube-title'][$i]) && strlen($postArray['youtube-title'][$i]) >= 8) ? $postArray['youtube-title'][$i] : 'Brak tytułu';
            $class->insertToDb(['title' => $title, 'file' => $filename, 'pack_id' => $postArray['pack_id']]);
            sleep(0.3);
        } catch (Exception) {
            continue;
        }
    }
}
