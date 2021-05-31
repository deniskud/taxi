<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
   .t1 {background-color: #eeeeee; font-weight: bold; text-align: center;}  
   .t2 {background-color: #ddddff; font-weight: normal; text-align: left;}  
  </style> 
 </head>
<body>
 <form action="showspb.php" method="get">

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


$host='localhost';
$database='4okna'; // имя базы данных
$user='4okna'; //user
$pswd='zeratul'; // пароль
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("Can not connect to MySQL.");

//building query
$sql = "SELECT * FROM spb WHERE dt > NOW()-INTERVAL $srok DAY ORDER BY $sort";
$result = mysqli_query($dbh,$sql) or die('query error: ' . mysql_error());
// Выводим результаты в html
$count=0;
echo "<table border=0>
<tr class=t1>
  <td>0#</td>
  <td>1<a href='?sort=id&srok=$srok'>id</a></td>
  <td width='160'>2<a href='?srok=$srok&sort=dt DESC'>datetime</a></td>
  <td>3<a href='?srok=$srok&sort=ip'>ip</a></td>
  <td>4<a href='?srok=$srok&sort=port DESC'>cnt</a></td>
  <td>5<a href='?srok=$srok&sort=sran'>useragent</a></td>
   <td>6</td>
   <td>7</td>
   <td>8</td>
   <td>9</td>
   <td>10</td>
   <td>11</td>
</tr>\n";
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
  echo "\t\t<td>".++$count."</td>
  <td>".$row[0]."</td>
  <td>".$row[1]."</td>
  <td><a href='showip.php?ip=".$row[2]."'>".$row[2]."</a></td>
  <td>".$row[3]."</td>
  <td><a href='showuser.php?usid=".$row[4]."'> ".$tmp."</a></td>\n 
   <td>".$row[5]."</td>
   <td>".$row[6]."</td>
   <td>".$row[7]."</td>
   <td>".$row[8]."</td>
   <td>".$row[9]."</td>
   <td>".$row[10]."</td>
";
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

