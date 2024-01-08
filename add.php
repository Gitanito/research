<?php
    if (isset($_POST['mytext']) && trim($_POST['mytext']) != "" ) {

        $outtext = [];

        $intext = explode(PHP_EOL, $_POST['mytext']);

        foreach($intext as $line) {
            if (trim($line) != "") {
                if (substr($line,0,1) === "[") { // this a citation-line
                    $line = str_replace("[", "[^", $line);
                    $line = str_replace("]", "]:", $line);
                } else {
                    $line = str_replace("[", "[^", $line);
                }
            }
            $outtext[] = $line;
        }
        file_put_contents('sources/test.md', join(PHP_EOL, $outtext));
    }
?>
<form method="post">
    <input type="submit"><br>
<textarea name="mytext"></textarea><br>
<input type="submit">
</form>