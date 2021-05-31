<?php

require "dbi.php";
if (!isset($_COOKIE['Ind_Counter'])) $_COOKIE['Ind_Counter'] = 0;
$_COOKIE['Ind_Counter']++;
SetCookie('Ind_Counter', $_COOKIE['Ind_Counter'], 0x6FFFFFFF);
$rprt=$_COOKIE['Ind_Counter'];
$rip=$_SERVER["REMOTE_ADDR"];  // ip
$rusr=$_SERVER["REMOTE_USER"];  //user
$rbrz=$_SERVER["HTTP_USER_AGENT"]; //browser&os

$source=@$_GET['utm_source'];
if (!@$source)$source=' ';
$medium=@$_GET['utm_medium'];
if (!@$medium) $medium=' ';
$campaign=@$_GET['utm_campaign'];
if (!@$campaign)$campaign=" ";
$content=@$_GET['utm_content'];
if (!@$content) $content=" ";
$term=@$_GET['utm_term'];
if (!@$term)$term=" ";

$host='localhost';
$database='4okna'; // имя базы данных
$user='4okna'; //user
$pswd='zeratul'; // пароль
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("Can not connect to MySQL.");
$sql="INSERT INTO od (ip,port,sran,source,medium,campaign,content,term) VALUES ('$rip','$rprt','$rbrz','$source','$medium','$campaign','$content','$term')";
    if (mysqli_query($dbh,$sql))
        mysqli_close($dbh);
    else
        echo mysqli_error($dbh);
$f = fopen("od.html", "r");
while(!feof($f)) print fgets($f);
fclose($f);

die();
?>

