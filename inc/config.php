<?php
return [
    'db' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'roundsound_cs',
    ],
    'upload' => [
        'path' => 'uploads/',
        'allowed_extensions' => ['mp3'],
        'max_size' => 10485760,
    ],
    'convert' => [
        'command_1' => 'ffmpeg -ss %s -t %s -i %s -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame %s',
        'command_2' => 'ffmpeg -i %s -f mp3 -b:a 128k -ar 44100 -codec:a libmp3lame -af "afade=t=in:st=0:d=3,afade=t=out:st=%d:d=3,volume=0.8" %s',
        'command_youtube' => 'yt-dlp --cookies /var/www/roundsound.cs.daffyy.pl/yt_cookies.txt -f 140 --max-filesize 9M --prefer-ffmpeg -x --audio-format mp3 -o %s %s',
    ]
];
