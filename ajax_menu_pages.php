<?php
$silent = true;
include_once "header.php";


$all = [];
$all_ = $db->query("SELECT * FROM wordindex;");
while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
    $all[$a['name']] = $a['value'];
}

function findPage($keyword) {
    global $all;

    if (strstr($keyword, '.html')) return $keyword;
    if (trim($keyword) != "") {
        if (isset($all[$keyword])) {
            if (strstr($all[$keyword], ".html")) {
                return $all[$keyword];
            } else {
                return findPage($all[$keyword]);
            }
        }
    }
}

if (isset($_GET['search']) && trim($_GET['search']) != "") {
    $searchwords = explode(" ", $_GET['search']);
    $q_add = [];
    foreach ($searchwords as $sw) {
        $q_add[] = " name LIKE '%".$sw."%' ";
    }

    $index = [];
    $all_ = $db->query("SELECT name,value FROM wordindex WHERE name LIKE '%".$_GET['search']."%' ;");
    while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
        if (substr($a['name'],0, 1) != "%") {
            if (strstr($a['value'], ".html")) {
                $out[] = "<li><a href='wiki/" . rawurlencode($a['value']) . "' target=main>" . $a['name'] . "</a>&nbsp;<a href='wordindex_edit.php?action=add&key=" . $a['name'] . "' target=main title='Ein Synonym hinzufügen'>&nbsp;+</a></li>";
            } else {
                $out[] = "<li><a href='wiki/" . rawurlencode(findPage($a['name'])) . "' target=main>" . $a['name'] . "</a>&nbsp;<a href='wordindex_edit.php?action=del&key=" . $a['name'] . "' target=main title='Synonym entfernen'>&nbsp;-</a></li>";
            }
            $index[] = $a['value'];
        }
    }
    $all_ = $db->query("SELECT name,value FROM wordindex WHERE ".join(" AND ", $q_add).";");
    while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
        if (substr($a['name'],0, 1) != "%") {
            if (!in_array($a['value'], $index)) {
                if (strstr($a['value'], ".html")) {
                    $out[] = "<li><a href='wiki/" . rawurlencode($a['value']) . "' target=main>" . $a['name'] . "</a>&nbsp;<a href='wordindex_edit.php?action=add&key=" . $a['name'] . "' target=main title='Ein Synonym hinzufügen'>&nbsp;+</a></li>";
                } else {
                    $out[] = "<li><a href='wiki/" . rawurlencode(findPage($a['name'])) . "' target=main>" . $a['name'] . "</a>&nbsp;<a href='wordindex_edit.php?action=del&key=" . $a['name'] . "' target=main title='Synonym entfernen'>&nbsp;-</a></li>";
                }
            }
        }
    }
    echo "<ul>".join("\n", $out)."<ul>";

}