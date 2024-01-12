<?php
$silent = true;
include_once "header.php";
include_once "functions_add.php";
include_once "simple_html_dom.php";

if (isset($_GET['mytitle']) && trim($_GET['mytitle']) != "" && isset($_GET['mylink']) && trim($_GET['mylink']) != "" ) {

    $_GET['mytitle'] = str_replace(',', '', $_GET['mytitle']); // because there are co many titles of pdfs with comma

    if (strstr($_GET['mylink'], '.pdf')) {
        file_put_contents("temp/pdf.pdf", file_get_contents($_GET['mylink']));
        exec('pdftotext -raw temp/pdf.pdf temp/pdf.txt');
        $intext = explode("\n", file_get_contents("temp/pdf.txt"));
        $inlink = $_GET['mylink'];
        $intitle = explode(',', $_GET['mytitle']);
        $intype = "link";
        unlink("temp/pdf.pdf");
        unlink("temp/pdf.txt");

    } else {
        $source = file_get_html($_GET['mylink']);
        $intext = explode("\n", $source->plaintext . "\n\n[1] " . $_GET['mylink']);
        $inlink = $_GET['mylink'];
        $pretitle = explode("   ", trim($source->plaintext))[0];
        $intitle = explode(',', $pretitle);
        $intype = "link";
    }
    add($intitle, $intext, $inlink, $intype);

}