<?php

require "dbi.php";

 
 
if (!isset($_COOKIE['Ind_Counter'])) $_COOKIE['Ind_Counter'] = 0;
$_COOKIE['Ind_Counter']++;
SetCookie('Ind_Counter', $_COOKIE['Ind_Counter'], 0x6FFFFFFF);
$rprt=$_COOKIE['Ind_Counter'];


$rip=$_SERVER["REMOTE_ADDR"];  // ip
$rusr=$_SERVER["REMOTE_USER"];  //user
$rbrz=$_SERVER["HTTP_USER_AGENT"]; //browser&os
// $rprt=$_SERVER["REMOTE_PORT"]; //port

//DB server
// $host='db.tzk104.nic.ua'; // имя хоста
// $database='iruppua6_clients'; // имя базы данных
// $user='iruppua6_zlodey'; //user
// $pswd='zeratul'; // пароль
$host='localhost';
$database='4okna'; // имя базы данных
$user='4okna'; //user
$pswd='zeratul'; // пароль
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("Can not connect to MySQL.");
//mysql_select_db($database) or die("Can not connect to MySQL.");

$sql="INSERT INTO spb (ip,port,sran) VALUES ('$rip','$rprt','$rbrz')";
    if (mysqli_query($dbh,$sql))
        mysqli_close($dbh);
    else
        echo mysqli_error($dbh);

$f = fopen("spb.html", "r");
while(!feof($f)) print fgets($f);
fclose($f);

die();
?>

