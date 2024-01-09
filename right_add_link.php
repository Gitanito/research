<?php
include_once "header.php";

if (isset($_POST['mytitle']) && trim($_POST['mytitle']) != "" && isset($_POST['mytext']) && trim($_POST['mytext']) != "" ) {

    include_once "simple_html_dom.php";
    $source = file_get_html($_POST['mytext']);
    $intext = explode("\n", $source->plaintext."\n\n[1] ".$_POST['mytext']);
    $intitle = explode(',', $_POST['mytitle']);

    include_once "functions_add.php";
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
                    <a class="nav-link" href="right_add_document.php">Dok.</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Neuen Link importieren</h5>
            <p class="card-text">


            <form method="post">
                <div class="form-group">
                    <label for="tit">Titel</label>
                    <input type="text" class="form-control" id="tit" aria-describedby="titleHelp" name="mytitle">
                    <small id="titleHelp" class="form-text text-muted">Mehrere mit Komma getrennt möglich</small>
                </div>
                <div class="form-group">
                    <label for="tex">URL / Link</label>
                    <input id="tex" class="form-control" name="mytext">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            </p>
        </div>
    </div>


<?php

include_once "footer.php";