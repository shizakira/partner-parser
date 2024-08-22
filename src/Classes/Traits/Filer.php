<?php

namespace App\Classes\Traits;

trait Filer
{
    private function getElemsFromRow($filePath)
    {
        $fd = fopen($filePath, 'r');
        while (!feof($fd)) {
            yield fgets($fd);
        }

        fclose($fd);
    }

    private function writeToFile($filePath, $fileContent)
    {
        file_put_contents($filePath, $fileContent . "\n", FILE_APPEND);
    }

    private function deleteEmptyRows($text)
    {
        return trim(preg_replace('/^[ \t]*[\r\n]+/m', ' ', $text));
    }

    private function writeErrorToLog($message, $e)
    {
        $time = date('H:i:s');
        $message = "Error in {$message}: {$e->getMessage()} $time";
        $this->writeToFile($this->logPath, $message);
    }
}
