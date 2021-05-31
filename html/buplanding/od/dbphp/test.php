<?php

$rip=$_SERVER["REMOTE_ADDR"];  // ip
$rusr=$_SERVER["REMOTE_USER"];  //user
$rbrz=$_SERVER["HTTP_USER_AGENT"]; //browser&os
$rprt=$_SERVER["REMOTE_PORT"]; //port
//DB server
$host='db.tzk104.nic.ua'; // имя хоста
$database='iruppua6_clients'; // имя базы данных, 
$user='iruppua6_zlodey'; //user 
$pswd='zeratul'; // пароль
$dbh = mysql_connect($host, $user, $pswd) or die("not connect MySQL.");
mysql_select_db($database) or die("not connect to db.");

$sql="INSERT INTO kiev (ip,port,sran) VALUES ('$rip','$rprt','$rbrz')";
    if (mysql_query($sql))
        mysql_close();
    else
        echo mysql_error();

$f = fopen("divversion.html", "r");
while(!feof($f)) print fgets($f);
fclose($f);

die();
?>

