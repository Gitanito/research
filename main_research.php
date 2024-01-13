<?php

include_once "header.php";

ob_implicit_flush(true);
ob_end_flush();

echo "<div id='loading' class='btn btn-danger' style='position:fixed;top:0;right:0;'>LOADING</div>";

$params = [];

$all = [];
$index = [];
$index_ = $db->query("SELECT * FROM wordindex;");
while ($a = $index_->fetchArray(SQLITE3_ASSOC)) {
    $index[$a['id']] = [$a['name'],$a['value']];
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

echo '<h1>'.$_GET['search'].'</h1>';
$params[] = "-e '".$_GET['search']."'";
$deeper = [];
$deeper_f = [];

$stmt  = $db->prepare ("SELECT name FROM wordindex WHERE value=:value;");
$stmt->bindValue(':value', $_GET['search'], SQLITE3_TEXT);
$result = $stmt->execute();
while ($word = $result->fetchArray()) {
    if (in_array(strtolower($word[0]), $deeper_f)) continue;

    $deeper[] = "<a href='?search=".$word[0]."'>".$word[0]."</a>";
    $deeper_f[] = strtolower($word[0]);
}
if (sizeof($deeper)) {
    echo '<div class="card" style="width:100%;"><div class="card-header">'
        . "<h3>Verwandte Suchthemen</h3>"
        . '</div>'
        . join(' ', $deeper)
        . '</div>';
}
$shown = [];

//echo "grep -Rwi 'sources/' ".join(' ', $params);
//die;

$fp=popen("grep -Rwi 'sources/' ".join(' ', $params),"r");


while (!feof($fp)) {
    $content = fgets($fp, 4096);

    $r = explode(":", $content);
    //print_r($r);
    //if (isset($r[1]) && substr($r[1], 0, 1) == "{") continue;
    try {

        $h_ = explode('/', $r[0]);
        if (!isset($h_[1]))  continue;
        $h = explode('.', $h_[1]);
        if (!isset($index[$h[0]][0]))  continue;
        $myfile = findPage($index[$h[0]][0]);
        if (!in_array($myfile, $shown)) {
            $shown[] = $myfile;

            echo '<div class="card" style="width:100%;"><div class="card-header">'
                . "<b><a href='wiki/" . rawurlencode(findPage($index[$h[0]][0])) . "'>" . $index[$h[0]][0] . "</a></b>"
                . '</div>'
                . $r[1]
                . '</div>';
        }
    } catch (Exception $e) {}
}

echo "<script>$('#loading').hide();</script>";
include_once "footer.php";
@pclose($fp);
@ob_end_clean();

