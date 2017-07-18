<?php

require_once __DIR__ . '/../src/UploadClass/DocumentUploader.php';

class DocumentUploadTest extends PHPUnit_Framework_TestCase {

    public function testUploadSmallFile() {
        $uploader = new DocumentUploader();
        $name =  "small_file.jpg";
        $filename = __DIR__ . "/testfiles/" . $name;
        $file = fopen($filename, "r");
        $content = fread($file, filesize($filename));
        fclose($file);
        $blob = base64_encode($content);
        $tmpname = filesize($filename) . '_%_' . $name;
        $path = "";
        $part = 0;
        $uploader->upload($blob, $tmpname, $path, $part);
        $this->assertTrue(file_exists(__DIR__ . '/../upload/' . $name));
    }

    public function testUploadBigFile() {
        $uploader = new DocumentUploader();
        $name =  "big_file.jpeg";
        $filename = __DIR__ . "/testfiles/" . $name;
        $file = fopen($filename, "r");
        $content = fread($file, filesize($filename));
        fclose($file);
        $blob = base64_encode($content);
        $tmpname = filesize($filename) . '_%_' . $name;
        $path = "";
        $part = 0;
        $uploader->upload($blob, $tmpname, $path, $part);
        $this->assertTrue(file_exists(__DIR__ . '/../upload/' . $name));
    }
}

