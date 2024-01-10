<?php

    function add($intitle, $intext, $inlink = "", $intype = "text", $inlang = "de")
    {
        global $_wordindex;

        try {


            $filename = trim($intitle[0]);

            $local_wordindex = [];

            foreach ($intitle as $title) {
                $title = trim($title);

                // Replaces all spaces with hyphens.
                $title = str_replace(' ', '-', $title);

                // Removes special chars.
                $title = preg_replace('/[^A-Za-z0-9\-]/', '', $title);
                // Replaces multiple hyphens with single one.
                $title = preg_replace('/-+/', ' ', $title);


                if (isset($_wordindex[$title])) { // Titel existiert - eine Verknüpfung wird erstellt
                    $local_wordindex[$title] = $_wordindex[$title];
                    $new_file = rand(1000, 9999);
                    $last_title = $filename . " " . $new_file;
                    $_wordindex["_additionals"][$title][] = $last_title;
                } else { // Titel existiert nicht - wird neu angelegt und mit dieser Seite verknüpft
                    if (!isset($last_title)) {
                        $_wordindex[$title] = $title . ".html";
                        $_wordindex[strtolower($title)] = $title;
                        $last_title = $title;
                    } else {
                        $_wordindex[$title] = $last_title;
                    }
                }
                $filename = $last_title;
            }

            $outtext = [];

            $sources = [];
            $sourcesl = [];

            foreach ($intext as $line) {
                if (trim(substr($line, 0, 1)) === "[") { // this a citation-line
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

            $out = join("<br>" . PHP_EOL, $outtext);

            $matches = [];
            $matchlist = [];
            foreach ($_wordindex as $searchwords => $outurl) {
                if (substr($searchwords, 0, 1) != "_") {
                    preg_match_all("/$searchwords/i", $out, $matches);
                    foreach ($matches[0] as $m) {
                        $matchlist[] = $m;
                    }
                }
            }
            $matchlist = array_values(array_filter($matchlist));

            $match_links = [];
            foreach ($matchlist as $ml) {
                if (trim($ml) != "") {
                    $match_links[$ml] = findPage(strtolower($ml));
                }
            }

            $keys = array_map('strlen', array_keys($match_links));
            array_multisort($keys, SORT_DESC, $match_links);

            $preout = $out;
            foreach ($match_links as $kw => $lnk) {
                $preout = preg_replace("/$kw/i", md5($kw), $preout);
            }
            foreach ($match_links as $kw => $lnk) {
                $z = md5($kw);
                $preout = preg_replace("/$z/i", "<a href='" . $lnk . "'>" . $kw . "</a>", $preout);
            }
            $out = $preout;

            $out .= '<br>' . join("<br>" . PHP_EOL, $sources);


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
</head>
<body><div class="container">'
                . '<h1>' . $filename . '</h1>'
                . (isset($inlink) ? "Link: <a target=_blank href='" . $inlink . "'>" . $inlink . "</a><br><br>" : "")
                . $out
                . '</div></body></html>';


            file_put_contents('wiki/' . $filename . ".html", $out);

            file_put_contents('sources/' . $filename . ".txt", ":" . serialize(['type' => $intype, 'mylink' => $inlink, 'mytitle' => $intitle, 'mylang' => $inlang]) . PHP_EOL . join(PHP_EOL, $intext));

            $wc = '<?php $_wordindex_ser = \'' . serialize($_wordindex) . '\';';
            file_put_contents('wordindex.php', $wc);


        } catch (Exception $e) {
            die;
        }
    }