<?php
/*
require "dbi.php";
if (!isset($_COOKIE['Ind_Counter'])) $_COOKIE['Ind_Counter'] = 0;
$_COOKIE['Ind_Counter']++;
SetCookie('Ind_Counter', $_COOKIE['Ind_Counter'], 0x6FFFFFFF);
$rprt=$_COOKIE['Ind_Counter'];
$rip=$_SERVER["REMOTE_ADDR"];  // ip
$rusr=$_SERVER["REMOTE_USER"];  //user
$rbrz=$_SERVER["HTTP_USER_AGENT"]; //browser&os
$host='localhost';
$database='4okna'; // имя базы данны
$user='4okna'; //user
$pswd='zeratul'; // пароль
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("Can not connect to MySQL.");
$sql="INSERT INTO spb (ip,port,sran) VALUES ('$rip','$rprt','$rbrz')";
    if (mysqli_query($dbh,$sql))
        mysqli_close($dbh);
    else
        echo mysqli_error($dbh);
*/

$f = fopen("index.html", "r");
while(!feof($f)) print fgets($f);
fclose($f);
die();
?>

