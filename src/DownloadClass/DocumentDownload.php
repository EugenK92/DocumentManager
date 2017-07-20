<?php
/**
 * Class DocumentUploader
 * Class for uploading Files (small and large ones)
 * Large ones in parts
 */
class DocumentDownload {
    private $rootdir;
    private $uploaddir;
    private $chunkWhole;
    private $chunkpart;

    public function __construct() {
        $config = simplexml_load_file(__DIR__ . "/../../config/config.xml") or die("Error: Cannot create object");
        $this->rootdir = (string)$config->RootDirectory;
        $this->uploaddir = (string)$config->UploadDirectory;
        $this->chunkWhole = (int)$config->WholeChunkSize;
        $this->chunkpart = (int)$config->PartChunkSize;
    }

    private function getContentType($file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $file);
    }

    public function downloadWholeDocument($name, $test = false) {
        $file = $this->uploaddir . '/' . $name;
        $contentType = $this->getContentType($file);
        $out = fopen($file, "r");
        $len = $this->chunkWhole;
        if ($test) {
            return filesize($file);
        }
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . filesize($file));
        while (!feof($out)) {
            $buffer = fread($out, $len);
            ob_flush();
            flush();
            echo $buffer;
        }
        fclose($out);
        exit;

    }

    public function downloadPartDocument($name, $part, $test = false) {
        $file = $this->uploaddir . '/' . $name;
        $contentType = $this->getContentType($file);
        $out = fopen($file, "r");

        //===============================================================================//
        $size = $this->chunkpart;
        // if filesize is greater
        // than the splitsize = size
        // else the splitsize = filesize
        $splitSize = filesize($file) >= $size ? $size : filesize($file);

        // if key exists
        // part = key
        // else part = 0
        $part = $part != null ? $part : 0;
        // if part == 0
        // part = 0
        // else part = part - 1
        $part = $part <= 0 ? 0 : $part - 1;

        $filesize = filesize($file);
        $fileparts = ceil( $filesize / $splitSize);

        if ($part >= $fileparts) {
            throw new Exception("Parts out of bounds!", 404);
        }

        $currentPosition = $splitSize * $part;

        //Calculate the rest of the file, if requested part ist larger then filesize
        $end = $splitSize * ($part + 1);
        if ($end > $filesize) {
            $splitSize = ($filesize - $currentPosition);
        }
        if ($test) {
            return $splitSize;
        }
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . $splitSize);
        header('HTTP_Content-Size: ' . $fileparts);
        header('HTTP_Current-Part: ' . ($part + 1));


        //Set Pointer in File (Read from that pointer)
        fseek($out, $currentPosition, SEEK_SET);

        if (!feof($out)) {
            $buffer = fread($out, $splitSize);
            ob_flush();
            flush();
            echo $buffer;
        }
        fclose($out);
        exit;
    }

}