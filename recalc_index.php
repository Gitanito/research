<?php

$cleanup = true;

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$db = new SQLite3('data/sqlite3database.db');


$offset = 0;

$xs  = ($db->query("SELECT value FROM settings where name='indexingstartpoint';"))->fetchArray()[0];


if ($cleanup) {
    $db->exec("UPDATE settings SET value='0' WHERE name='indexingstartpoint';");
    $db->exec("DELETE FROM wordindex;");
    $db->exec("delete from sqlite_sequence where name='wordindex';");
    $db->exec("DELETE FROM additionals;");
    $db->exec("delete from sqlite_sequence where name='additionals';");
}


$all = [];
$all_ = $db->query("SELECT * FROM wordindex;");
while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
    $all[$a['name']] = $a['value'];
}


    try {

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

        $startid = ($db->query("SELECT value FROM settings where name='indexingstartpoint';"))->fetchArray()[0];

        $all = [];
        $all_ = $db->query("SELECT * FROM wordindex;");
        while ($a = $all_->fetchArray(SQLITE3_ASSOC)) {
            $all[$a['name']] = $a['value'];
        }


        //$entries = $_content->findAll(["_id" => "asc"],999999, $xs[0]["value"]); //, 10, $offset
        $entriescount = ($db->query("SELECT count(*) FROM content"))->fetchArray()[0];
        $entries = $db->query("SELECT * FROM content order by id asc LIMIT 999999 OFFSET ".$startid.";");
        $ekey = 0;
        while ($entry = $entries->fetchArray(SQLITE3_ASSOC)) {
            $filecontent = file_get_contents("data/contents/".$entry["id"].".txt");
            $intext = explode(PHP_EOL, $filecontent);
            unset($intext[0]);

            $intitle = explode(',', trim(str_replace("\u{00a0}", ' ', $entry['mytitle'])));
            echo $ekey+1 . " von ". $entriescount . " : ".$intitle[0]."\n";

            $db->exec("UPDATE settings SET value='".($startid + $ekey)."' WHERE name='indexingstartpoint';");

            $titlestack = [];
            foreach ($intitle as $t) {
                $titlestack[] = $t;

                $special = explode("|", $t);
                if (isset($special[1])) {
                    $t = trim($special[0]);
                }
                $special = explode("(", $t);
                if (isset($special[1])) {
                    $t = trim($special[0]);
                }
                $special = explode("[", $t);
                if (isset($special[1])) {
                    $t = trim($special[0]);
                }
                $special = explode(" - ", $t);
                if (isset($special[1])) {
                    $t = trim($special[0]);
                }
                $titlestack[] = $t;

                $spaced = explode(" ", $t);
                if (sizeof($spaced) > 2) {
                    $titlestack[] = $spaced[0]." ".$spaced[1]." ".$spaced[2];
                }
                if (sizeof($spaced) > 3) {
                    $titlestack[] = $spaced[0]." ".$spaced[1]." ".$spaced[2]." ".$spaced[3];
                }

            }
            //$keys = array_map('strlen', array_keys($titlestack));
            //array_multisort($keys, SORT_ASC, $titlestack);

            $last_title = "";

            foreach ($titlestack as $title) {
                $title = trim($title);
                if (strlen($title) < 5) continue;
                /*
                                // Replaces all spaces with hyphens.
                                $title = str_replace(' ', '-', $title);

                                // Removes special chars.
                                $title = preg_replace('/[^A-Za-z0-9\-]/', '', $title);
                                // Replaces multiple hyphens with single one.
                                $title = preg_replace('/-+/', ' ', $title);
                */
                if (isset($all[$title])) { // Titel existiert - eine Verknüpfung wird erstellt
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
            }
            $ekey++;
        }
        //echo "<script>window.setTimeout(function(){window.location='recalc_index.php?offset=".($offset + 10)."';}, 1000)</script>";
    } catch (Exception $e) {}