<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$db = new SQLite3('../data/sqlite3database.db');

$db-> exec("CREATE TABLE IF NOT EXISTS settings(
   id INTEGER PRIMARY KEY AUTOINCREMENT, 
   name TEXT NOT NULL,
   value TEXT NOT NULL)");
$db->exec("INSERT INTO settings (name,value) VALUES ('indexingstartpoint', '0')");

$db-> exec("CREATE TABLE IF NOT EXISTS wordindex(
   id INTEGER PRIMARY KEY AUTOINCREMENT, 
   name TEXT NOT NULL,
   value TEXT NOT NULL,
   ishead INTEGER NOT NULL DEFAULT 0,
   type TEXT NOT NULL DEFAULT 'txt')");

$db-> exec("CREATE TABLE IF NOT EXISTS wordcloud(
   id INTEGER PRIMARY KEY AUTOINCREMENT, 
   name TEXT NOT NULL,
   value TEXT NOT NULL )");

$db-> exec("CREATE TABLE IF NOT EXISTS additionals(
   id INTEGER PRIMARY KEY AUTOINCREMENT, 
   name TEXT NOT NULL,
   value TEXT NOT NULL)");

$db-> exec("CREATE TABLE IF NOT EXISTS content(
   id INTEGER PRIMARY KEY AUTOINCREMENT, 
   name TEXT NOT NULL,
   type TEXT NOT NULL,
   mylink TEXT NOT NULL,
   mytitle TEXT NOT NULL,
   mylang TEXT NOT NULL,
   content TEXT NOT NULL)");

//die;

/*
$db->exec("DELETE FROM content;");
$db->exec("delete from sqlite_sequence where name='content';");
$directory = '../sources'; // Replace with the actual directory path
$entries = scandir($directory);
foreach ($entries as $entry) {
    if ($entry !== '.' && $entry !== '..') {
        $path = $directory . '/' . $entry;
        if (is_file($path)) {
            $file = json_decode(file_get_contents($path));
            $content = $file->content;
            unset($file->content);
            unset($file->_id);

            $stmt  = $db->prepare ("INSERT INTO content (name,type,mylink,mytitle,mylang,content) values (:name,:type,:mylink,:mytitle,:mylang,'');");
            $stmt->bindValue(':name', $file->name, SQLITE3_TEXT);
            $stmt->bindValue(':type', $file->type, SQLITE3_TEXT);
            $stmt->bindValue(':mylink', $file->mylink, SQLITE3_TEXT);
            $stmt->bindValue(':mytitle', $file->mytitle, SQLITE3_TEXT);
            $stmt->bindValue(':mylang', $file->mylang, SQLITE3_TEXT);
            $stmt->execute();

            $result = $db->query("SELECT last_insert_rowid();");
            $exists  = $result->fetchArray();

            $firstline = json_encode($file);
            $filecontent = $firstline.PHP_EOL.$content;
            file_put_contents("../sources/".$exists[0].".txt", $filecontent);
        }
    }
}*/