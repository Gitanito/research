<?php
include_once "header.php";
$_wordcloud = new \SleekDB\Store("wordcloud", __DIR__ . "/sdb/data");
$_additionals = new \SleekDB\Store("additionals", __DIR__ . "/sdb/data");
$_content = new \SleekDB\Store("content", __DIR__ . "/sdb/data");

$_wordindex->deleteStore();
$_wordcloud->deleteStore();
$_additionals->deleteStore();
$_content->deleteStore();

$files = glob('sources/*'); //get all file names
foreach($files as $file){
    if(is_file($file))
        unlink($file); //delete file
}

$files = glob('wiki/*'); //get all file names
foreach($files as $file){
    if(is_file($file))
        unlink($file); //delete file
}

echo "Everything cleared - you can close this window!";
