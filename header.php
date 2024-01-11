<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once "sdb/Store.php";
$_wordindex = new \SleekDB\Store("wordindex", __DIR__ . "/sdb/data");

/*
$_wordindex_ser = [];
include_once ("wordindex.php");
$_wordindex = unserialize($_wordindex_ser);


$indexStore = new \SleekDB\Store("myindex", __DIR__ . "/sdb/data");

$article = [
    "title" => "Google Pixel XL",
    "about" => "Google announced a new Pixel!",
    "author" => [
        "avatar" => "profile-12.jpg",
        "name" => "Foo Bar"
    ]
];
$results = $indexStore->insert($article);
$allNews = $indexStore->findAll();

print_r($allNews);
$indexStore->deleteStore();
*/

function findPage($keyword) {
    global $_wordindex;

    if (strstr($keyword, '.html')) return $keyword;
    if (trim($keyword) != "") {
        $k = $_wordindex->findBy(["name", "=", $keyword]);
        if (isset($k[0]['value'])) {
            if (strstr($k[0]['value'], ".html")) {
                return $k[0]['value'];
            } else {
                return findPage($k[0]['value']);
            }
        }
    }
}
if (!isset($silent) || !$silent) {
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap_<?=$_COOKIE['backendstyle']?>.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body>
<?php } ?>