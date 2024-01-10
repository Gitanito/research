<?php
ini_set('memory_limit', '512M');
include_once "header.php";
include_once "functions_add.php";
include_once "simple_html_dom.php";

if (isset($_POST['mytitle']) && trim($_POST['mytitle']) != "" && isset($_POST['mylink']) && trim($_POST['mylink']) != "" ) {

    $source = file_get_html($_POST['mylink']);
    $intext = explode("\n", $source->plaintext."\n\n[1] ".$_POST['mylink']);
    $inlink = $_POST['mylink'];
    $intitle = explode(',', $_POST['mytitle']);
    $intype = "link";
    add($intitle, $intext, $inlink, $intype);

    if (isset($_POST['mysubs']) && $_POST['mysubs'] === "1") {
        $baseurl = parse_url($_POST['mylink']);
        $regexp = "(.*?)<a .*?href=\"(.*?)\".*?>(.*?)<\/a>(.*)";
        if(preg_match_all("/$regexp/m", file_get_contents($_POST['mylink']), $matches, PREG_SET_ORDER)) {

            foreach($matches as $match) {
                // $match[2] = link address
                // $match[3] = link text
                $link = $match[2];
                switch (substr($match[2],0,1)) {
                    case "/":
                        $link = $baseurl["scheme"].'://'.$baseurl["host"].$match[2];
                        break;
                    case "h":
                        $link = $match[2];
                        break;
                    default:
                        $link = $baseurl["scheme"].'://'.$baseurl["host"].$baseurl["path"].'/'.$match[2];
                }
                $source = file_get_html($link);
                $intext = explode("\n", $source->plaintext."\n\n[1] ".$link);
                $inlink = $link;
                $intitle = explode(',', $match[3]);
                $intype = "link";
                add($intitle, $intext, $inlink, $intype);
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
                    <a class="nav-link active" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="right_add_video.php">Video</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="right_add_bulk.php">Bulk</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Neuen Link importieren</h5>
            <p class="card-text">


            <form method="post">
                <input type="hidden" name="type" value="link">
                <div class="form-group">
                    <label for="tit">Titel</label>
                    <input type="text" class="form-control" id="tit" aria-describedby="titleHelp" name="mytitle">
                    <small id="titleHelp" class="form-text text-muted">Mehrere mit Komma getrennt möglich</small>
                </div>
                <div class="form-group">
                    <label for="tex">URL / Link</label>
                    <input id="tex" class="form-control" name="mylink">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="mysubs" value="1" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Sub-Links importieren</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            </p>
        </div>
    </div>


<?php

include_once "footer.php";