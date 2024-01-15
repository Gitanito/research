<?php
    $left = "left_nav_topics.php";
    $main = "";
    $upload = "right_add_multi.php";
    $right = "right_add_text.php";

if (isset($_GET['left']) && trim($_GET['left']) != "") $left = $_GET['left'];
    if (isset($_GET['main']) && trim($_GET['main']) != "") $main = $_GET['main'];
    if (isset($_GET['upload']) && trim($_GET['upload']) != "") $upload = $_GET['upload'];
    if (isset($_GET['right']) && trim($_GET['right']) != "") $right = $_GET['right'];
?>
<frameset cols="300,*,300"  framespacing="0" id="all" frameborder="0">
    <frame src="<?=$left?>" name="left" id="left"  framespacing="0" />
    <frame src="<?=$main?>" name="main" id="main"  framespacing="0" />
    <frameset rows="200,*"  framespacing="0" frameborder="0">
        <frame src="<?=$upload?>" name="upload" id="upload"  framespacing="0" />
        <frame src="<?=$right?>" name="right" id="right"  framespacing="0" />
    </frameset>
</frameset>