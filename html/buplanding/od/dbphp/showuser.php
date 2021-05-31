<?php
require "../dbi.php";
$usid=$_GET['usid'];
$sort=$_GET['sort'];
if (!$sort) $sort="id";


$host='localhost';
$database='4okna'; // имя базы данных
$user='4okna'; //user
$pswd='zeratul'; // пароль
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("Can not connect to MySQL.");
$sql = "SELECT * FROM kiev WHERE sran=\"".$usid."\" ORDER BY ".$sort;
$result = mysqli_query($dbh,$sql) or die('query error: ' . mysqli_error($dbh));
// Выводим результаты в html

echo "<table border=1>
<tr>
 <td>#</td>\n
 <td><a href=\"?sort=id&usid=$usid\">\nd</a>\n</td>\n
 <td width='160'><a href=\"?sort=dt&usid=$usid"."\">datetime</a></td>\n
 <td><a href=\"?sort=ip&usid=$usid"."\">ip</a></td>\n
 <td>useragent</td>\n
</tr>\n";
$cc=0;
while ($row = mysqli_fetch_row($result)) {
    echo "\t<tr>\n";
        echo "\t\t<td>".++$cc."</td><td>".$row[0]."</td><td>".$row[1]."</td><td><a href='showip.php?ip=".$row[2]."'>".$row[2]."</a></td><td><font size=-2>".$row[4]."</font></td>\n";
    echo "\t</tr>\n";
}
echo "</table>\n";
// Освобождаем память от результата
mysqli_free_result($result);
echo("all ok\n");


mysqli_close($dbh);

die();
?>

