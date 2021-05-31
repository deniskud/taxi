<?php
$host='localhost';
$database='d939941g_4okna'; // имя базы данных
$user='d939941g_4okna'; //user
$pswd='b*4@*1]1'; // пароль

#$host='db.tzk104.nic.ua'; // имя хоста
#$database='iruppua6_clients'; // имя базы данных, 
#$user='iruppua6_zlodey'; //user 
#$pswd='zeratul'; // пароль
 
$dbh = mysqli_connect($host, $user, $pswd) or die("not connect MySQL.");
mysqli_select_db($database) or die("not connect to db.");

#$sql="CREATE TABLE `br` (`br_id` INT(5) NOT NULL AUTO_INCREMENT, `brname` VARCHAR(250), PRIMARY KEY(`br_id`), INDEX(`brname`));";
#    if (mysqli_query($sql))
#        echo "ok!";
#    else
#        echo "error!";

$sql="SHOW TABLES;";
$result = mysqli_query($sql);
if (!$result) {
    die('Неверный запрос: ' . mysqli_error());
}
echo($result);
die();
?>

