<?php
include_once "header.php";
include_once "functions_add.php";

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
    } else if ($_POST['mydl'] == 'pl') {
        shell_exec('cd temp/playlist/ && rm * && ../../yt-dlp --write-subs --write-auto-subs --sub-langs ' . $_POST['mylang'] . ' --sub-format ttml --no-download --yes-playlist "' . $_POST['mylink'] . '"');

        $directory = 'temp/playlist/'; // Replace with the actual directory path
        $entries = scandir($directory);
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
                }
            }
        }

    }
}
?>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="right_add_text.php">Text</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="right_add_link.php">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Video</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="right_add_bulk.php">Bulk</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Neues Video importieren</h5>
            <p class="card-text">


            <form method="post">
                <input type="hidden" name="type" value="video">
                <div class="form-group">
                    <label for="tit">Titel</label>
                    <input type="text" class="form-control" id="tit" aria-describedby="titleHelp" name="mytitle">
                    <small id="titleHelp" class="form-text text-muted">Mehrere mit Komma getrennt möglich</small>
                </div>
                <div class="form-group">
                    <label for="tex">URL / Link</label>
                    <input id="tex" class="form-control" name="mylink">
                </div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="radio" name="mylang" id="option1" value="de" checked> DE
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="mylang" id="option2" value="en"> EN
                    </label>
                </div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="radio" name="mydl" id="option11" value="sv" checked> Single Video
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="mydl" id="option12" value="pl"> Playlist
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            </p>
        </div>
    </div>


<?php

include_once "footer.php";