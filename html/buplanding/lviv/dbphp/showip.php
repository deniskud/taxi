
<?php
 //DB server

$ip=$_GET["ip"];

$host='localhost';
$database='4okna'; // имя базы данных
$user='4okna'; //user
$pswd='zeratul'; // пароль
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("Can not connect to MySQL.");
$sql = "SELECT * FROM lviv WHERE ip='".$ip."'";
$result = mysqli_query($dbh,$sql) or die('query error: ' . mysql_error());
// Выводим результаты в html


echo "<a href='https://www.whois.com/whois/$ip' target='_blank'> WHO IS $ip</a>";
echo "<table border=1><tr><td>#</td><td>id</td><td width='160'>datetime</td><td>ip</td><td>useragent</td></tr>\n";
$cc=0;

while ($row = mysqli_fetch_row($result)) {

    echo "<tr>\n";
    echo "<td>".++$cc."</td>\n
    <td>".$row[0]."</td>\n
    <td>".$row[1]."</td>\n
    <td>".$row[2]."</td>\n
    <td><a href='showuser.php?usid=".$row[4]."'><font size=-2>".$row[4]."</font></a></td>\n";
    echo "\t</tr>\n";
}
echo "</table>\n";
// Освобождаем память от результата
mysqli_free_result($result);
echo($cc." records<br>all ok\n");


mysqli_close($dbh);

die();
?>



