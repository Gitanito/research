<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

    $_wordindex_ser = [];
    include_once ("wordindex.php");
    $_wordindex = unserialize($_wordindex_ser);

    function findPage($keyword) {
        global $_wordindex;
        //print_r($keyword);echo "<br><br><br>";
        $fp = $_wordindex[$keyword];
        if (strstr($fp,".md")) {
            return $fp;
        } else {
            return findPage($fp);
        }
    }

    if (isset($_POST['mytitle']) && trim($_POST['mytitle']) != "" && isset($_POST['mytext']) && trim($_POST['mytext']) != "" ) {
        $intitle = explode(',', $_POST['mytitle']);
        $intext = explode(PHP_EOL, $_POST['mytext']);
        $filename = trim($intitle[0]);


        $local_wordindex = [];

        foreach($intitle as $title) {
            $title = trim($title);
            if (isset($_wordindex[$title])) { // Titel existiert - eine Verknüpfung wird erstellt
                $local_wordindex[$title] = $_wordindex[$title];
                $new_file = rand(1000,9999);
                $last_title = $filename." ".$new_file;
                $_wordindex["_additionals"][$title][] = $last_title;
            } else { // Titel existiert nicht - wird neu angelegt und mit dieser Seite verknüpft
                if (!isset($last_title)) {
                    $_wordindex[$title] = $title . ".md";
                    $last_title = $title;
                } else {
                    $_wordindex[$title] = $last_title;
                }
            }
            $filename = $last_title;
        }



        $outtext = [];

        $sources = [];

        foreach($intext as $line) {
            if (substr($line,0,1) === "[") { // this a citation-line
                $l = explode(" ", $line);
                $index = str_replace(['[',']'], "", $l[0]);
                $sources[$index] = '<a name="cit'.$index.'">['.$index.']</a> <a target=_blank href="'.trim($l[1]).'">'.trim($l[1]).'</a>';
            } else {
                $outtext[] = $line;
            }
        }

        foreach($outtext as $tkey => $tline) {
            foreach($sources as $k => $s) {
                $tline = str_replace('['.$k.']', '<sup><a href="#cit'.$k.'">['.$k.']</a></sup>',$tline);
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
                    $matchlist[] = $m;
                }
            }
        }
        $matchlist = array_values(array_filter($matchlist));

        $match_links = [];
        foreach ($matchlist as $ml) {
            $match_links[$ml] = findPage($ml);
        }

        foreach($match_links as $kw => $lnk) {
            $out = preg_replace("/$kw/i", "<a href='" . $lnk . "'>" . $kw . "</a>", $out);
        }


        $out .= '<br>'.join("<br>".PHP_EOL, $sources);

        file_put_contents('wiki/'.$filename . ".md", $out);
        file_put_contents('sources/'.$filename . ".txt", $intext);

        $wc = '<?php $_wordindex_ser = \''.serialize($_wordindex).'\';';
        file_put_contents('wordindex.php', $wc);

    }
?>
<form method="post">
    <input type="submit"><br>
    <h2>Titel (mehrere mit Komma getrennt möglich)</h2>
    <input name="mytitle" type="text">

    <h2>Text</h2>
<textarea name="mytext"></textarea><br>
<input type="submit">
</form>