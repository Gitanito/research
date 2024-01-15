<?php
include_once "header.php";
include_once "functions_add.php";

if (isset($_POST['mytitle']) && trim($_POST['mytitle']) != "" && isset($_POST['mytext']) && trim($_POST['mytext']) != "" ) {
    $intitle = explode(',', $_POST['mytitle']);
    $intext = explode(PHP_EOL, $_POST['mytext']);
    $inlink = $_POST['mylink'];
    $intype = "text";
    add($intitle, $intext, $inlink, $intype);
}
?>


    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Text</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="right_add_link.php">Link</a>
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
            <h5 class="card-title">Neuen Text importieren</h5>
            <p class="card-text">


            <form method="post">
                <input type="hidden" name="type" value="text">
                <div class="form-group">
                    <label for="tit">Titel</label>
                    <input type="text" class="form-control" id="tit" aria-describedby="titleHelp" name="mytitle">
                    <small id="titleHelp" class="form-text text-muted">Mehrere mit Komma getrennt möglich</small>
                </div>
                <div class="form-group">
                    <label for="tex">Text</label>
                    <textarea id="tex" class="form-control" name="mytext"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-check-label" for="lnk">(optional) Link</label>
                    <input type="text" class="form-control" id="lnk" aria-describedby="linkHelp" name="mylink">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="istopic">
                    <label class="form-check-label" for="exampleCheck1">Ich habe noch keine Funktion</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            </p>
        </div>
    </div>


<?php

include_once "footer.php";