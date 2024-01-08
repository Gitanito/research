<?php
    if (isset($_POST['mytext']) && trim($_POST['mytext']) != "" ) {

        $outtext = [];

        $intext = explode(PHP_EOL, $_POST['mytext']);


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
print_r($sources);
        foreach($outtext as $tkey => $tline) {
            foreach($sources as $k => $s) {
                $tline = str_replace('['.$k.']', '<sup><a href="#cit'.$k.'">['.$k.']</a></sup>',$tline);
            }
            $outtext[$tkey] = $tline;
        }

        $out =  join("<br>".PHP_EOL, $outtext);
        $out .= join("<br>".PHP_EOL, $sources);
        file_put_contents('sources/test.md', $out);
    }
?>
<form method="post">
    <input type="submit"><br>
<textarea name="mytext"></textarea><br>
<input type="submit">
</form>