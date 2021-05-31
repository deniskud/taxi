<?php
#$host='db.tzk104.nic.ua'; // имя хоста
#$database='iruppua6_clients'; // имя базы данных, 
#$user='iruppua6_zlodey'; //user 
#$pswd='zeratul'; // пароль

$host='localhost';
$database='d939941g_4okna'; // имя базы данных
$user='d939941g_4okna'; //user
$pswd='b*4@*1]1'; // пароль

$dbh = mysql_connect($host, $user, $pswd) or die("not connect MySQL.");
mysql_select_db($database) or die("not connect to db.");

$sql="SELECT * FROM br;";

$result = mysql_query($sql);
if (!$result) {
    die('Неверный запрос: ' . mysql_error());
}

while($row = mysql_fetch_array($result))
{
echo "id: ".$row['br_id']."<&nbsp>";
echo "name: ".$row['brname']."<br>\n";
}
echo("ok");
die();
?>

