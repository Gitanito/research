<?php

include_once "header.php";
include_once "functions_add.php";
include_once "simple_html_dom.php";

if (isset($_POST['mytext']) && trim($_POST['mytext']) != "" ) {


    $lines = explode("\n", $_POST['mytext']);

    foreach ($lines as $csvline) {
        $tupels = explode(" ", trim($csvline));
        $inlink = end($tupels);
        unset($tupels[sizeof($tupels)-1]);
        $it = join(" ", $tupels);

        $intitle = explode(',', $it);

        if (strstr($inlink, "youtu")) {

            shell_exec ('./yt-dlp --write-subs --write-auto-subs --sub-langs '.$_POST['mylang'].' --sub-format ttml --no-download "'.$inlink.'" --output temp/subtitles.txt');
            $intext = [];
            $intext_ = explode("\n", file_get_contents("temp/subtitles.txt.".$_POST['mylang'].".ttml"));

            foreach($intext_ as $intext_line) {
                if (substr($intext_line,0, 8) == "<p begin") {
                    $o = explode(">", $intext_line);
                    $oo = explode("<", $o[1]);
                    if (substr($oo[0],0, 1) != "[") {
                        $intext[] = $oo[0];
                    }
                }
            }

            $intype = "video";

        } else {
            $source = file_get_html($inlink);
            $intext = explode("\n", $source->plaintext."\n\n[1] ".$inlink);
            $intype = "link";
        }
        add($intitle, $intext, $inlink, $intype, $_POST['mylang']);
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
                    <a class="nav-link" href="right_add_video.php">Video</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Bulk</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Bulk-import</h5>
            <p class="card-text">


            <form method="post">
                <input type="hidden" name="type" value="bulk">
                <p>Bulk-Import für Links und Videos. Syntax: <b>{titel}{space}{link}{new line}</b></p>
                <div class="form-group">
                    <label for="tex">URL / Link</label>
                    <textarea id="tex" class="form-control" name="mytext"></textarea>
                </div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="radio" name="mylang" id="option1" value="de" checked> DE
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="mylang" id="option2" value="en"> EN
                    </label>
                </div>
                <p>Für Videos auswählen</p>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            </p>
        </div>
    </div>


<?php

include_once "footer.php";