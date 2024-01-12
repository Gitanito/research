<?php

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


function add($intitle, $intext, $inlink = "", $intype = "text", $inlang = "de")
    {
        global $db, $all;

        try {


            $filename = trim($intitle[0]);

            foreach ($intitle as $title) {
                $title = str_replace("\u{00a0}", ' ', trim($title));

                if (isset($all[$title])) { // Titel existiert - eine Verknüpfung wird erstellt
                    $orgcontent = file_get_contents("data/contents/".$x[0].".txt");
                    $orgcontent = $orgcontent.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.$intext;
                    file_put_contents("data/contents/".$x[0].".txt", $orgcontent);
                } else { // Titel existiert nicht - wird neu angelegt und mit dieser Seite verknüpft
                    if (!isset($last_title) || $last_title == "") {

                        $stmt  = $db->prepare ("INSERT INTO wordindex (name,value,ishead) values (:name,:value,:ishead);");
                        $stmt->bindValue(':name', $title, SQLITE3_TEXT);
                        $stmt->bindValue(':value', preg_replace("/[[\-]+/i", '-', preg_replace("/[^a-z0-9\_\-\.]/i", '-', basename(trim($title)))) . ".html", SQLITE3_TEXT);
                        $stmt->bindValue(':ishead', 1, SQLITE3_TEXT);
                        $stmt->execute();

                        $stmt  = $db->prepare ("INSERT INTO wordindex (name,value) values (:name,:value);");
                        $stmt->bindValue(':name', strtolower($title), SQLITE3_TEXT);
                        $stmt->bindValue(':value', $title, SQLITE3_TEXT);
                        $stmt->execute();

                        $all[$title] = preg_replace("/[[\-]+/i", '-', preg_replace("/[^a-z0-9\_\-\.]/i", '-', basename(trim($title)))) . ".html";
                        $all[strtolower($title)] = $title;

                        $last_title = $title;
                    } else {
                        $stmt  = $db->prepare ("INSERT INTO wordindex (name,value) values (:name,:value);");
                        $stmt->bindValue(':name', $title, SQLITE3_TEXT);
                        $stmt->bindValue(':value', $last_title, SQLITE3_TEXT);
                        $stmt->execute();

                        $stmt  = $db->prepare ("INSERT INTO wordindex (name,value) values (:name,:value);");
                        $stmt->bindValue(':name', strtolower($title), SQLITE3_TEXT);
                        $stmt->bindValue(':value', $last_title, SQLITE3_TEXT);
                        $stmt->execute();

                        $all[$title] = $last_title;
                        $all[strtolower($title)] = $last_title;
                    }
                }
                $filename = $last_title;
            }

            $stmt  = $db->prepare ("INSERT INTO content (name,type,mylink,mytitle,mylang,content) values (:name,:type,:mylink,:mytitle,:mylang,'');");
            $stmt->bindValue(':name', $filename, SQLITE3_TEXT);
            $stmt->bindValue(':type', $intype, SQLITE3_TEXT);
            $stmt->bindValue(':mylink', $inlink, SQLITE3_TEXT);
            $stmt->bindValue(':mytitle', join(',',$intitle), SQLITE3_TEXT);
            $stmt->bindValue(':mylang', $inlang, SQLITE3_TEXT);
            $stmt->execute();

            $result = $db->query("SELECT last_insert_rowid();");
            $exists  = $result->fetchArray();

            $firstline = json_encode(['type' => $intype, 'mylink' => $inlink, 'mytitle' => join(',',$intitle), 'mylang' => $inlang]);
            $filecontent = $firstline. PHP_EOL . join(PHP_EOL, $intext);
            file_put_contents("sources/".$exists[0].".txt", $filecontent);

        } catch (Exception $e) {
            die;
        }
    }