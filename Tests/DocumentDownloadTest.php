<?php

require_once __DIR__ . '/../src/UploadClass/DocumentUploader.php';
require __DIR__ . '/../src/DownloadClass/DocumentDownload.php';

class DocumentDownloadTest extends PHPUnit_Framework_TestCase {

    private function uploadTestFile($fname) {
        $uploader = new DocumentUploader();
        $name = $fname;
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

    public function testDownloadWholeFile() {
        $name = "small_file.jpg";
        $this->uploadTestFile($name);
        $downloader = new DocumentDownload();
        $result = $downloader->downloadWholeDocument($name, true);
        $this->assertEquals($result, filesize(__DIR__ . "/testfiles/" . $name));
    }

//    public function testDownloadPartOfFile() {
//
//    }
}