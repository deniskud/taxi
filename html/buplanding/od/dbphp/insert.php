<?php
#$host='db.tzk104.nic.ua'; // имя хоста
#$database='iruppua6_clients'; // имя базы данных, 
#$user='iruppua6_zlodey'; //user 
#$pswd='zeratul'; // пароль

$host='localhost';
$database='d939941g_4okna'; // имя базы данных
$user='d939941g_4okna'; //user
$pswd='b*4@*1]1'; // пароль

$rip=$_SERVER["REMOTE_ADDR"];
$rbrz=$_SERVER["HTTP_USER_AGENT"];
$rprt=$_SERVER["REMOTE_PORT"];

$date=date("d.m.y"); // число.месяц.год 
$time=date("H:i"); // часы:минуты:секунды 


$dbh = mysql_connect($host, $user, $pswd) or die("not connect MySQL.");
mysql_select_db($database) or die("not connect to db.");

$sql="INSERT INTO os (osname) VALUE ('test2 os22');";

$result = mysql_query($sql);
if (!$result) {
    die('Неверный запрос: ' . mysql_error());
}
echo("ok");
die();
?>

