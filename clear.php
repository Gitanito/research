<?php
$wc = '<?php $_wordindex_ser = \'' . serialize([]) . '\';';
file_put_contents('wordindex.php', $wc);

$wc = '<?php $_wordcloud_ser = \'' . serialize([]) . '\';';
file_put_contents('wordcloud.php', $wc);

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
