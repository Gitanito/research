<?php
include_once "header.php";

if (isset($_POST['mytitle']) && trim($_POST['mytitle']) != "" && isset($_POST['mytext']) && trim($_POST['mytext']) != "" ) {
    $intitle = explode(',', $_POST['mytitle']);
    $intext = explode(PHP_EOL, $_POST['mytext']);
    $inlink = $_POST['mylink'];
    include_once "functions_add.php";
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
                    <a class="nav-link" href="right_add_document.php">Dok.</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Neuen Text importieren</h5>
            <p class="card-text">


            <form method="post">
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
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            </p>
        </div>
    </div>


<?php

include_once "footer.php";