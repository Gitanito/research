<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

    $_wordindex_ser = [];
    include_once ("wordindex.php");
    $_wordindex = unserialize($_wordindex_ser);

    function findPage($keyword) {
        global $_wordindex;
        //if (trim($keyword) != "") return "";
        //echo "-".$keyword."-";
        //if ($keyword == "") die;
        $fp = $_wordindex[$keyword];
        if (strstr($fp, ".md")) {
            return str_replace('.md', '.html', $fp);
        } else {
            return findPage($fp);
        }

    }

    $directory = 'sources'; // Replace with the actual directory path
    $entries = scandir($directory);
    foreach ($entries as $entry) {
        if ($entry !== '.' && $entry !== '..') {
            $path = $directory . '/' . $entry;
            if (is_file($path)) {


                $intitle = explode('.', $entry);
                $intext = explode(PHP_EOL, file_get_contents($path));
                $filename = trim($intitle[0]);


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

                $out =  join("<br>".PHP_EOL, $outtext);

                $matches = [];
                $matchlist = [];
                foreach ($_wordindex as $searchwords => $outurl) {
                    if (substr($searchwords,0,1) != "_") {
                        preg_match_all("/$searchwords/i", $out, $matches);
                        foreach ($matches[0] as $m) {
                            if ($m != "") {
                                $matchlist[] = $m;
                            }
                        }
                    }
                }
                $matchlist = array_unique(array_values(array_filter($matchlist)));

                //print_r($matchlist);
                $match_links = [];
                foreach ($matchlist as $ml) {
                    $match_links[$ml] = findPage($ml);
                }
                //die;
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

                file_put_contents('wiki/'.$filename . ".md", $out);


            }
        }
    }
