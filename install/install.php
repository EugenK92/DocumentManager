<?php
/**
 * Installationscript for DocumentManager
 */

echo "\nWelcome to the installationguide for the DocumentManager.\n\n";

//check if libraries exists
echo "Let me check for necessary libraries.\n";
if (!extension_loaded('xml')) {
    echo "\033[1;31mXML library not found. \n\n";
    exit;
}
echo "\033[1;32mXML library successfully found.\033[1;37m\n";
if (!extension_loaded('zip')) {
    echo "\033[1;31mZip Library not found. \n\n";
    exit;
}
echo "\033[1;32mZip library successfully found.\033[1;37m\n";

// create directories
$rootdir = __DIR__;
$rootdirarr = explode('/', $rootdir);
$rootdir = "";
for ($i = 1; $i < sizeof($rootdirarr) - 2; $i++) {
    $rootdir .=  '/' . $rootdirarr[$i];
}

$configdir = $rootdir . '/DocumentManager/config';
$uploaddir = $rootdir . '/DocumentManager/upload';
$archivedir = $rootdir . '/DocumentManager/archive';

echo "Tell me, where is the root directory? [\033[1;34m" . $rootdir . "\033[1;37m] ";
$path = readline();
$rootdir = $path == "" ? $rootdir : $path;

echo "Tell me, where is the upload directory? [\033[1;34m" . $rootdir . "/DocumentManager/upload/\033[1;37m] ";
$path = readline();
$uploaddir = $path == "" ? $uploaddir : $path;

echo "Tell me, where is the archive directory? [\033[1;34m" . $rootdir . "/DocumentManager/archive/\033[1;37m] ";
$path = readline();
$archivedir = $path == "" ? $archivedir : $path;

if (!is_dir($configdir)) {
    mkdir($configdir, 0755, true);
}
if (!is_dir($uploaddir)) {
    mkdir($uploaddir, 0755, true);
}
if (!is_dir($archivedir)) {
    mkdir($archivedir, 0755, true);
}

echo "Create config.xml file.\n";
//create config.xml
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml_directories = $xml->createElement("Directories");
$xml_rootdir = $xml->createElement("RootDirectory", $rootdir);
$xml_uploaddir = $xml->createElement("UploadDirectory", $uploaddir);
$xml_archivedir = $xml->createElement("ArchiveDirectory", $archivedir);

$xml_directories->appendChild($xml_rootdir);
$xml_directories->appendChild($xml_uploaddir);
$xml_directories->appendChild($xml_archivedir);
$xml->appendChild($xml_directories);
$xml->save($configdir . "/config.xml");
echo "\n";
