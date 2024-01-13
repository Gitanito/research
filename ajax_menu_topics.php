<?php
$silent = true;
include_once "header.php";

if (isset($_GET['search']) && trim($_GET['search']) != "") {
    $searchwords = explode(" ", $_GET['search']);
    $q_add = [];
    foreach ($searchwords as $sw) {
        $q_add[] = " name LIKE '%".$sw."%' ";
    }

    $all = [];
    $all_ = $db->query("SELECT name,value FROM wordindex WHERE ishead=1 AND  name LIKE '%".$_GET['search']."%' ;");
    while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
        if (substr($a['name'],0, 1) != "%") {
            $out[] =  "<li><a href='wiki/" . rawurlencode($a['value']). "' target=main>" . $a['name'] . "</a></li>";
        }
    }
    $all_ = $db->query("SELECT name,value FROM wordindex WHERE ishead=1 AND ".join(" AND ", $q_add).";");
    while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
        if (substr($a['name'],0, 1) != "%") {
            $out[] =  "<li><a href='wiki/" . rawurlencode($a['value']). "' target=main>" . $a['name'] . "</a></li>";
        }
    }
    echo "<ul>".join("\n", $out)."<ul>";
}