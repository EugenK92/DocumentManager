# DocumentManager
DocumentManager is a tool, which helps you to upload and download Files.
You can manage small files but also large ones.
The download can be a whole file at once or in smaller parts.

Feel free to use, change and/or improve it!

<h1>Installation</h1>
To install the DocumentManager in the right directory, navigate
to the <strong>install/</strong> directory and<br>
execute <strong>php install.php</strong> then follow the instructions<br>
<br>
![Image](https://github.com/EugenK92/DocumentManager/blob/master/readme_assets/install.png)

<h1>Usage</h1>
<h2>Upload</h2>
The minimal parameters for the Documentupload:<br>

```php
    $manager = new DocumentUploader();
    $blob = $_POST['blob'];
    $tmpname = $_POST['tmpname'];
    $path = $_POST['path'];
    $part = $_POST['part'];
    return $manager->upload($blob, $tmpname, $path, $part);
```

<strong>$blob</strong> - A part of a Document or the whole Document encoded as BLOB<br>
<strong>$tmpname</strong> - The temporary name for a Document with the syntax: <br>

```php
$tmpname = filesize($filename) . '_%_' . $name;
```

<strong>$path</strong> - If the Upload is a directory tree, the path to the file; if not path is an empty string<br>
<strong>$part</strong> - Part of the uploaded document