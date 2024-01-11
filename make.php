<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once "sdb/Store.php";
$_wordindex = new \SleekDB\Store("wordindex", __DIR__ . "/sdb/data");
$_wordcloud = new \SleekDB\Store("wordcloud", __DIR__ . "/sdb/data");
$_content = new \SleekDB\Store("content", __DIR__ . "/sdb/data");

    try {

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

        $entries = $_content->findAll();
        foreach ($entries as $entry) {

            $intext = explode(PHP_EOL, $entry["content"]);

            $intitle = str_replace("\u{00a0}", ' ', trim(explode(',', $entry['mytitle'])[0]));

/*
            // Replaces all spaces with hyphens.
            $intitle = str_replace(' ', '-', $intitle);

            // Removes special chars.
            $intitle = preg_replace('/[^A-Za-z0-9\-]/', '', $intitle);
            // Replaces multiple hyphens with single one.
            $intitle = preg_replace('/-+/', ' ', $intitle);
*/
            $filename = trim($intitle);


            $local_wordindex = [];

            $outtext = [];

            $sources = [];
            $sourcesl = [];

            foreach($intext as $line) {
                if (substr($line,0,1) === "[") { // this a citation-line
                    $l = explode(" ", $line);
                    $index = str_replace(['[',']'], "", $l[0]);
                    $sources[$index] = '<a name="cit'.$index.'">['.$index.']</a> <a target=_blank href="'.trim($l[1]).'">'.trim($l[1]).'</a>';
                    $sourcesl[$index] = '<a target=_blank href="'.trim($l[1]).'">^</a>';
                } else {
                    $outtext[] = $line;
                }
            }

            foreach($outtext as $tkey => $tline) {
                foreach($sources as $k => $s) {
                    $tline = str_replace('['.$k.']', '<sup><a href="#cit'.$k.'">['.$k.']</a> '.$sourcesl[$k].'</sup>',$tline);
                }
                $outtext[$tkey] = $tline;
            }
//print_r($outtext);
            $out =  join("<br>".PHP_EOL, $outtext);

            $matches = [];
            $matchlist = [];
            foreach ($_wordindex->findAll() as $obj) {
                $v = $obj['name'];
                if (substr($v,0,1) != "_") {
                    preg_match_all("/$v/i", $out, $matches);
                    foreach ($matches[0] as $m) {
                        if ($m != "") {
                            $matchlist[] = $m;
                        }
                    }
                }
            }
            $matchlist = array_unique(array_values(array_filter($matchlist)));

            $match_links = [];
            foreach ($matchlist as $ml) {
                if (trim($ml) != "") {
                    $match_links[$ml] = findPage(strtolower($ml));

                    $t = $_wordcloud->findBy([["name", "=", $filename], ["value", "=", $ml]]);
                    if (!isset($t[0]["value"])) {
                        $_wordcloud->updateOrInsert(["name" => $filename, "value" => $ml]);
                    }
                    $t = $_wordcloud->findBy([["name", "=", $ml], ["value", "=", $filename]]);
                    if (!isset($t[0]["value"])) {
                        $_wordcloud->updateOrInsert(["name" => $ml, "value" => $filename]);
                    }
                }
            }

            $keys = array_map('strlen', array_keys($match_links));
            array_multisort($keys, SORT_DESC, $match_links);

            $preout = $out;
            foreach($match_links as $kw => $lnk) {
                $preout = preg_replace("/$kw/i", md5($kw), $preout);
            }

            foreach($match_links as $kw => $lnk) {
                $z = md5($kw);
                $preout = preg_replace("/$z/i", "<a href='" . $lnk . "'>" . $kw . "</a>", $preout);
            }
            $out = $preout;

            $out .= '<br>'.join("<br>".PHP_EOL, $sources);


            $out =
                '<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>document.addEventListener("DOMContentLoaded", function(){window.addEventListener("scroll", function() {if (window.scrollY > 50) {document.getElementById("navbar_top").classList.add("fixed-top"); navbar_height = document.querySelector(".navbar").offsetHeight;document.body.style.paddingTop = navbar_height + "px";} else {document.getElementById("navbar_top").classList.remove("fixed-top");document.body.style.paddingTop = "0";}});});</script>
    <script src="../frontend.js"></script>
    </head>
<body><div class="container">'
                . '<div class="card w-90"><div class="card-header" id="navbar_top"><h1>'.$filename.'</h1>'
                . (isset($entry['mylink'])?"Link: <a target=_blank href='".$entry['mylink']."'>".$entry['mylink']."</a><br><br>":"")
                . '</div><div class="card-body">'
                . $out
                . '</div></div></div></body></html>';



            file_put_contents('wiki/'.$filename . ".html", $out);

        }

    } catch (Exception $e) {}