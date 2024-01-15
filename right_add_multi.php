<?php
include_once "header.php";
include_once "functions_add.php";

$alert = "";

if (isset($_POST['mytitle']) && trim($_POST['mytitle']) != "" && isset($_POST['mylink']) && trim($_POST['mylink']) != "" ) {

    if ($_POST['mydl'] == 'sv') {
        shell_exec('./yt-dlp --write-subs --write-auto-subs --sub-langs ' . $_POST['mylang'] . ' --sub-format ttml --no-download "' . $_POST['mylink'] . '" --output temp/subtitles.txt');
        $intext = [];
        $intext_ = explode("\n", file_get_contents("temp/subtitles.txt." . $_POST['mylang'] . ".ttml"));

        foreach ($intext_ as $intext_line) {
            if (substr($intext_line, 0, 8) == "<p begin") {
                $o = explode(">", $intext_line);
                $oo = explode("<", $o[1]);
                if (substr($oo[0], 0, 1) != "[") {
                    $intext[] = $oo[0];
                }
            }
        }

        $intitle = explode(',', $_POST['mytitle']);
        $inlink = $_POST['mylink'];
        $intype = "video";
        add($intitle, $intext, $inlink, $intype, $_POST['mylang']);
        $alert = '<div class="alert alert-success" role="alert">Das Video <b>'.$_POST['mytitle'].'</b> wurde importiert!</div>';
    } else if ($_POST['mydl'] == 'pl') {
        $cmd = 'cd temp/playlist/ || true && rm * || true && ../../yt-dlp --write-subs --write-auto-subs --sub-langs ' . $_POST['mylang'] . ' --sub-format ttml --no-download --yes-playlist "' . $_POST['mylink'] . '"';
        //echo $cmd;
        shell_exec($cmd);

        $directory = 'temp/playlist/'; // Replace with the actual directory path
        $entries = scandir($directory);
        $importcount = 0;
        foreach ($entries as $entry) {
            if ($entry !== '.' && $entry !== '..') {
                $path = $directory . '/' . $entry;
                if (is_file($path)) {
                    $fcontent = file_get_contents($path);

                    $intext = [];
                    $intext_ = explode("\n", file_get_contents($path));

                    foreach ($intext_ as $intext_line) {
                        if (substr($intext_line, 0, 8) == "<p begin") {
                            $o = explode(">", $intext_line);
                            $oo = explode("<", $o[1]);
                            if (substr($oo[0], 0, 1) != "[") {
                                $intext[] = $oo[0];
                            }
                        }
                    }

                    $name_ = explode("/", $path);
                    $name = str_replace('.'.$_POST['mylang'].'.ttml', '', end($name_));
                    $namex = explode("[", $name);
                    $vidid = str_replace(']', '', $namex[1]);

                    $intitle = explode(',', $_POST['mytitle'].",".$namex[0]);
                    $inlink = "https://www.youtube.com/watch?v=".$vidid;
                    $intype = "video";
                    add($intitle, $intext, $inlink, $intype, $_POST['mylang']);
                    $importcount++;
                }
            }
        }
        $alert = '<div class="alert alert-success" role="alert"><b>'.$importcount.'</b> Videos von <b>'.$_POST['mytitle'].'</b> wurden importiert!</div>';
        shell_exec('cd temp/playlist/ || true && rm * || true && ');
    }
}
echo $alert;
?>
    <script>
        $(document).ready(function(){
            $("input").on('drop', function(e) {
                e.preventDefault();
                var data = e.originalEvent.dataTransfer.getData("text");
                console.log(data);
                if (data.indexOf("youtu") !== -1) { // something from youtube
                    if (data.indexOf("&list") !== -1) { // a playlist
                        // TODO: Fertigmachen
                    }
                }
                //droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
            });
        });
    </script>
<form method="post">
    <input type="hidden" name="mytitle" id="mytitle" value="">
    <input type="text" name="mylink" style="width:100%;height:198px;" placeholder="Drop Video or Link here">
</form>
<?php

include_once "footer.php";