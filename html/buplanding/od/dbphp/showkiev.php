<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
   .t1 {background-color: #eeeeee; font-weight: bold; text-align: center;}  
   .t2 {background-color: #ddddff; font-weight: normal; text-align: left;}  
  </style> 
 </head>
<body>
 <form action="showkiev.php" method="get">

<?php

require "../dbi.php";

$sort=$_GET['sort'];
if (!$sort) $sort='id';
$srok=$_GET['srok'];
if (!$srok) $srok=7;
$bot=$_GET['bot'];
if (!$bot) $bot=0;


echo "\n<input type='text' value='$srok' name='srok'>\n<input type='checkbox' name='bot' value='1'";
if ($bot==1) echo "checked";
echo ">не показывать ботов <input type='submit' value='обновить'></form>\n";


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

//building query
$sql = "SELECT * FROM kiev WHERE dt > NOW()-INTERVAL $srok DAY ORDER BY $sort";
$result = mysqli_query($dbh,$sql) or die('query error: ' . mysql_error());
// Выводим результаты в html
$count=0;
echo "<table border=0>
<tr class=t1>
<td>#</td><td><a href='?sort=id&srok=$srok'>id</a></td><td width='160'><a href='?srok=$srok&sort=dt DESC'>datetime</a></td><td><a href='?srok=$srok&sort=ip'>ip</a></td><td><a href='?srok=$srok&sort=port DESC'>cnt</a></td><td><a href='?srok=$srok&sort=sran'>useragent</a></td></tr>\n";
$i=0;
while ($row = mysql_fetch_row($result)) {
  $tmp=$row[4];
  $bf=0;
  $bf=strpos($tmp,'bot');
  if ($bf > 0) {
   $tmp="<font size='-3' color='#afafaf'>".$tmp."</font>";
  }
  else {
   $tmp="<font size='-2' color='#000000'>".$tmp."</font>";
  }
  
  if ($bf>0) {
    if ($bot==1){
      continue;
    }
  }
   
  
    $i++;
    $cla='';
    if ( round($i/2) == ($i/2) ) $cla="class=t2";
    echo "\t<tr $cla >\n";
    echo "\t\t<td>".++$count."</td><td>".$row[0]."</td><td>".$row[1]."</td><td><a href='showip.php?ip=".$row[2]."'>".$row[2]."</a></td><td>".$row[3]."</td><td><a href='showuser.php?usid=".$row[4]."'> ".$tmp."</a></td>\n";
    echo "\t</tr>\n";
  
  
  
}
echo "</table>\n";
// Освобождаем память от результата
mysql_free_result($result);
echo($count." records.\nall ok\n");


mysqli_close($dbh);
echo "</body>\n</html>";
die();
?>

