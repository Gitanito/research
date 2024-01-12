<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');


$db = new SQLite3('data/sqlite3database.db');
$db->exec("DELETE FROM wordcloud;");
$db->exec("delete from sqlite_sequence where name='wordcloud';");



$all = [];
$all_ = $db->query("SELECT * FROM wordindex;");
while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
    $all[$a['name']] = $a['value'];
}

    try {

        function findPage($keyword) {
            global $all;

            if (strstr($keyword, '.html')) return $keyword;
            if (trim($keyword) != "") {
                if (isset($all[$keyword])) {
                    if (strstr($all[$keyword], ".html")) {
                        return $all[$keyword];
                    } else {
                        return findPage($all[$keyword]);
                    }
                }
            }
        }

        $entriescount = ($db->query("SELECT count(*) FROM content"))->fetchArray()[0];
        $entries = $db->query("SELECT * FROM content order by id asc;");
        $ekey = 0;
        while ($entry = $entries->fetchArray(SQLITE3_ASSOC)) {

            $intitle = str_replace("\u{00a0}", ' ', trim(explode(',', $entry['mytitle'])[0]));
            echo $ekey+1 . " von ". $entriescount. " : ".$intitle."\n";

                /*
                            // Replaces all spaces with hyphens.
                            $intitle = str_replace(' ', '-', $intitle);

                            // Removes special chars.
                            $intitle = preg_replace('/[^A-Za-z0-9\-]/', '', $intitle);
                            // Replaces multiple hyphens with single one.
                            $intitle = preg_replace('/-+/', ' ', $intitle);
                */
            $filename = preg_replace("/[[\-]+/i", '-', preg_replace("/[^a-z0-9\_\-\.]/i", '-', basename(trim($intitle))));
            if (strlen($filename) < 6) continue;

            if (strlen($entry["content"]) < 200000) {
                $filecontent = file_get_contents("sources/".$entry["id"].".txt");
                $intext = explode(PHP_EOL, $filecontent);
                unset($intext[0]);

                $local_wordindex = [];

                $outtext = [];

                $sources = [];
                $sourcesl = [];

                foreach ($intext as $line) {
                    if (substr($line, 0, 1) === "[") { // this a citation-line
                        $l = explode(" ", $line);
                        $index = str_replace(['[', ']'], "", $l[0]);
                        $sources[$index] = '<a name="cit' . $index . '">[' . $index . ']</a> <a target=_blank href="' . trim($l[1]) . '">' . trim($l[1]) . '</a>';
                        $sourcesl[$index] = '<a target=_blank href="' . trim($l[1]) . '">^</a>';
                    } else {
                        $outtext[] = $line;
                    }
                }

                foreach ($outtext as $tkey => $tline) {
                    foreach ($sources as $k => $s) {
                        $tline = str_replace('[' . $k . ']', '<sup><a href="#cit' . $k . '">[' . $k . ']</a> ' . $sourcesl[$k] . '</sup>', $tline);
                    }
                    $outtext[$tkey] = $tline;
                }
                //print_r($outtext);
                $out = join("<br>" . PHP_EOL, $outtext);

                $matches = [];
                $matchlist = [];
                foreach ($all as $v => $obj) {
                    if (substr($v, 0, 1) != "_") {
                        preg_match_all("/" . preg_quote($v, '/') . "/i", $out, $matches);
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

                        $stmt  = $db->prepare ("INSERT INTO wordcloud (name,value) values (:name,:value);");
                        $stmt->bindValue(':name', $filename, SQLITE3_TEXT);
                        $stmt->bindValue(':value', $ml, SQLITE3_TEXT);
                        $stmt->execute();

                    }
                }

                $keys = array_map('strlen', array_keys($match_links));
                array_multisort($keys, SORT_DESC, $match_links);

                $preout = $out;
                foreach ($match_links as $kw => $lnk) {
                    $preout = preg_replace("/" . preg_quote($kw, '/') . "/i", md5($kw), $preout);
                }

                foreach ($match_links as $kw => $lnk) {
                    $z = md5($kw);
                    $preout = preg_replace("/" . preg_quote($z, '/') . "/i", "<a href='" . $lnk . "'>" . $kw . "</a>", $preout);
                }
                $out = $preout;

                $out .= '<br>' . join("<br>" . PHP_EOL, $sources);

            } else {
                $out = "Es handelt sich um ein Buch. Bitte nutzen Sie den Link!";
            }

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
                . '<div class="card w-90"><div class="card-header" id="navbar_top"><h1>'.trim($intitle).'</h1>'
                . (isset($entry['mylink'])?"Link: <a target=_blank href='".$entry['mylink']."'>".$entry['mylink']."</a><br><br>":"")
                . '</div><div class="card-body">'
                . $out
                . '</div></div></div></body></html>';



            file_put_contents('wiki/'. $filename . ".html", $out);
            $ekey++;
        }

    } catch (Exception $e) {}