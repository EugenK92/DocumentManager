<?php

/**
 * Class DocumentUploader
 * Class for uploading Files (small and large ones)
 * Large ones in parts
 */
class DocumentUploader {

    private $rootdir;
    private $uploaddir;
    private $filename;

    public function __construct() {
        $config = simplexml_load_file(__DIR__ . "/../../config/config.xml") or die("Error: Cannot create object");
        $this->rootdir = (string)$config->RootDirectory;
        $this->uploaddir = (string)$config->UploadDirectory;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    private function createDir($dir) {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }

    private function createHierarchy($hierarchy) {
        $arr = explode("/", $hierarchy);
        for ($i = 0; $i < sizeof($arr) - 1; $i++) {
            $this->createDir($arr[$i]);
            chdir($arr[$i]);
        }
    }

    private function changeToUploadDir($count) {
        for ($i = 0; $i < $count; $i++) {
            chdir('../');
        }
    }

    private function zipDir($dir) {
        $rootPath = realpath($dir);
        $zip = new ZipArchive();
        $zip->open($dir . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            if (!$file->isDir())
            {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    public function upload($blob, $originalTmpname, $path, $originalPart) {
        chdir($this->uploaddir);
        $currentFile = base64_decode($blob);
        $tmpName = $originalTmpname;
        $hierarchy = $path;
        $this->createHierarchy($hierarchy);
        $this->filename = explode("_%_", $tmpName)[1];
        if (file_exists($this->filename)) {
            $part = $originalPart;
            if ($part == 0) {
                unlink($this->filename);
            }
        }
        try {
            $file = fopen($this->filename, 'a');
            fwrite($file, $currentFile);
            fclose($file);
            if (strpos($hierarchy, "/") != false) {
                $this->changeToUploadDir(substr_count($hierarchy, "/"));
                $dir =  explode("/", $hierarchy)[0];
                $this->zipDir($dir);
                $this->uploadFileName = $this->uploaddir . "/" . $dir . ".zip";
            }
            else {
                $this->uploadFileName = $this->uploaddir . "/" . $this->filename;
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
        return ['code' => 200, 'message' => 'File successfully uploaded!'];
    }
}
