<?php
    $left = "left_nav_topics.php";
    $main = "";
    $right = "right_add_text.php";

    if (isset($_GET['left']) && trim($_GET['left']) != "") $left = $_GET['left'];
    if (isset($_GET['main']) && trim($_GET['main']) != "") $main = $_GET['main'];
    if (isset($_GET['right']) && trim($_GET['right']) != "") $right = $_GET['right'];
?>
<frameset cols="320,*"  framespacing="0"  frameborder="0">
    <frameset rows="50%,50%"  framespacing="0"  frameborder="0">
        <frame src="<?=$left?>" name="left"  framespacing="0" />
        <frame src="<?=$right?>" name="right"  framespacing="0" />
    </frameset>
    <frame src="<?=$main?>" name="main"  framespacing="0" />

</frameset>