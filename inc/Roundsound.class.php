<?php
class Roundsound
{
    protected $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/config.php';
    }

    protected function getConfig() : array
    {
        return $this->config;
    }

    public function getConfigSegment(string $segment) : array
    {
        return $this->getConfig()[$segment];
    }

    protected function databaseHandler() : PDO
    {
        $db = $this->getConfig()['db'];
        $dsn = sprintf('mysql:host=%s;dbname=%s', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass']);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function insertToDb(array $file): bool
    {
        try {
            $pdo = $this->databaseHandler();
            $sql = 'INSERT INTO `files` (`title`, `file`, `pack_id`, `ip`) VALUES (:title, :file, :pack_id, :ip)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $file['title']);
            $stmt->bindValue(':file', $file['file']);
            $stmt->bindValue(':pack_id', $file['pack_id']);
            $stmt->bindValue(':ip', $file['ip']);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTimes(string $time): array
    {
        $time = explode("-", $time);
        $timeFrom = DateTime::createFromFormat('i:s', $time[0]);

        $timeTo = DateTime::createFromFormat('i:s', $time[1]);
        $timeTo = $timeTo->getTimestamp() - $timeFrom->getTimestamp();

        $timeMinutesFrom = ($timeFrom->format('i') * 60) + $timeFrom->format('s');

        return ['from' => $timeMinutesFrom, 'to' => $timeTo];
    }

    public function downloadZip(string $pack_id, int $limit = 5): void
    {
        $pdo = $this->databaseHandler();
        if ($pack_id === 'random') {
            $sql = 'SELECT * FROM `files` WHERE `verified` = 1 ORDER BY RAND() LIMIT ' . $limit;
        } else {
            $sql = 'SELECT `title`, `file` FROM `files` WHERE `pack_id` = :pack_id';
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':pack_id', $pack_id);
        $stmt->execute();
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($files)) {
            die('Invalid pack_id!');
        }
        $files_string = "";
        $config_string = "MVP\n\"Piosenki\"\n{\n";
        $filename = 'uploads/pack/ ' . $pack_id . '.zip';
        $zip = new ZipArchive();
        $zip->open($filename, ZipArchive::CREATE);
        foreach ($files as $file) {
            $files_string .= $file['title'] . ' - ' . str_replace('uploads/', '', $file['file']) . PHP_EOL;
            $config_string .= '   "' . $file['title'] . '"' . PHP_EOL . '   { ' . PHP_EOL . '      "Path"  "' . str_replace('uploads/', '', $file['file']) . '"' . PHP_EOL . '      "Flags"  ""' . PHP_EOL . '   }' . PHP_EOL;
            $zip->addFile($file['file'], str_replace('uploads/', '', $file['file']));
        }
        $config_string .= "}";

        $config_string .= "\n\n\n";
        $config_string .= "Abner Res\n";
        $config_string .= "\"Abner Res\"\n{\n";
        foreach ($files as $file) {
            $config_string .= '   "' . str_replace(['uploads/'], '', $file['file']) . '"' . PHP_EOL . '   { ' . PHP_EOL . '       "' . $file['title'] . '"' . PHP_EOL . '   }' . PHP_EOL;
        }
        $config_string .= "}";
        $files_string .= "\n\nPobrano z https://roundsound.cs.daffyy.pl";
        $zip->addFromString('lista.txt', $files_string);
        $zip->addFromString('wygenerowany_config.txt', $config_string);

        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $filename);
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        unlink($filename);
    }

    public function greaterThanCount(int $limit = 5): bool
    {
        $count = $this->getFilesCount();
        if ($count >= $limit) {
            return true;
        } else {
            return false;
        }
    }

    public function getFilesCount() : int
    {
        $pdo = $this->databaseHandler();
        $sql = 'SELECT COUNT(*) FROM `files` WHERE `verified` = 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count;
    }

    public function removeInvalidFiles(): bool
    {
        try {
            $pdo = $this->databaseHandler();
            $sql = 'SELECT `file` FROM `files`';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $files = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $filesUploads = glob('uploads/*.mp3');

            die(var_dump($filesUploads));
            foreach ($filesUploads as $file) {
                if (!in_array($file, $files)) {
                    unlink($file);
                }
            }

            foreach ($files as $file) {
                if (!file_exists($file)) {
                    $sql = 'DELETE FROM `files` WHERE `file` = :file';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':file', $file);
                    $stmt->execute();
                }
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getIpAddress(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }
}
