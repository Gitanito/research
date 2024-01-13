<?php
include_once "header.php";
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

$stmt  = $db->prepare ("SELECT name FROM wordindex WHERE value=:value;");
$stmt->bindValue(':value', $_GET['search'], SQLITE3_TEXT);
$result = $stmt->execute();
while ($word = $result->fetchArray()) {
    $params[] = "-e '".$word[0]."'";
}

echo '<h1>'.$_GET['search'].'</h1>';

exec("grep -Rwi 'sources/' ".join(' ', $params)." 2>&1 &", $output);

$shown = [];
foreach($output as $line) {
    $r = explode(":", $line);
    $h_ = explode('/', $r[0]);
    $h = explode('.', $h_[1]);

    $myfile = findPage($index[$h[0]][0]);
    if (!in_array($myfile, $shown)) {
        $shown[] = $myfile;

        echo '<div class="card" style="width:100%;"><div class="card-header">'
            . "<b><a href='wiki/" . rawurlencode(findPage($index[$h[0]][0])) . "'>" . $index[$h[0]][0] . "</a></b>"
            . '</div>'
            . $r[1]
            . '</div>';
        ob_flush();
    }
}