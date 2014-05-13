<?php
// defines database connection data
define('DB_HOST', 'localhost');
define('DB_USER', 'testuser');
define('DB_PASSWORD', 'linux');
define('DB_DATABASE', 'ooOefening'); 

$sitename='/ooOefening';// OPGELET !! moet de subdirectory in je webserver zijn om te werken 
$basepath=$_SERVER['DOCUMENT_ROOT'];
$servername='192.168.1.40';// could be $_SERVER['HTTP_HOST']
$serverpath= array(
    "basepath" => $basepath,
    "site" =>$basepath.$sitename,
    "css" =>$basepath.$sitename.'/css',
    "db" =>$basepath.$sitename.'/db',
    "di" =>$basepath.$sitename.'/DI_Containers',
    "images" =>$basepath.$sitename.'/images',
    "objects" =>$basepath.$sitename.'/objects',
    "com" =>$basepath.$sitename.'/com',
    "outside_com" =>$servername.$sitename.'/com',
    "outside_root" =>$servername.$sitename
        );
?>
