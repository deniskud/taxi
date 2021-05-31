<?php

$host='localhost'; // имя хоста
$database='4okna'; // имя базы данных, 
$user='4okna'; //user 
$pswd='zeratul'; // пароль
 
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("not connect MySQL.");
$sql="CREATE TABLE od (
id INT NOT NULL AUTO_INCREMENT,
dt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
ip VARCHAR(16),
port INT(5),
sran VARCHAR(255),
source VARCHAR(16),
medium VARCHAR(10),
campaign VARCHAR(10),
content VARCHAR(36),
term VARCHAR(255),
PRIMARY KEY(id) );";

if (mysqli_query($dbh,$sql)) echo "ok!";
else echo "error!";
echo("all ok\n");
mysqli_close($dbh);


die();
?>

