<?php
include_once "header.php";

$files = glob('wiki/*'); //get all file names
foreach($files as $file){
    if(is_file($file))
        unlink($file); //delete file
}

echo "Wiki cleared - you can close this window!";
