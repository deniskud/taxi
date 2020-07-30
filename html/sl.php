<?php

$t=0;
$head ="<!DOCTYPE html>
<html>
 <head>
  <title>zmeys leliks</title>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <style>
   .t1 {background-color: #eeeeee; font-weight: bold; text-align: center;}  
   .t2 {background-color: #ddddff; font-weight: normal; text-align: left;}  
  </style> 
 </head>
<body>
";
$head .=" <form action='showlelik.php' method='get'>";


echo $head;



//require "dbi.php";

$sort=$_GET['sort'];
if (!$sort) $sort='id';

//$srok=$_GET['srok'];
if (!$srok) $srok=7;


echo "\n<input type='text' value='$srok' name='srok'>\n";//<input type='checkbox' name='bot' value='1'";
//if ($bot==1) echo "checked";
echo "<input type='submit' value='обновить'></form>\n";

/*
$host='localhost';
$database='taxi'; // имя базы данных
$user='zmey'; //user
$pswd='kalina'; // пароль
//$dbh = mysqli_connect($host, $user, $pswd, $database) or die("Can not connect to MySQL.");
$link = mysqli_connect("127.0.0.1", "zmey", "kalina", "taxi");
if (!$link) {
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
echo "Соединение с MySQL установлено!" . PHP_EOL;
echo "Информация о сервере: " . mysqli_get_host_info($link) . PHP_EOL;
mysqli_close($link);
*/



$dbh = mysqli_connect("127.0.0.1", "zmey", "kalina", "taxi");
if (!$dbh) {
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
//building query
//echo "<br>sort=$sort<br>";
$sql = "SELECT * FROM leliki ORDER BY $sort";// ORDER BY $sort";
$result = mysqli_query($dbh,$sql) or die('query error: ' . mysql_error());
// Выводим результаты в html
$count=0;
echo "<table border=0>
<tr class=t1>
  <td>0#</td>
  <td><a href='?sort=id&srok=$srok'>id</a></td>
  <td width='160'><a href='?srok=$srok&sort=name'>name</a></td>
  <td><a href='?srok=$srok&sort=fam'>fam</a></td>
  <td><a href='?srok=$srok&sort=tel'>tel</a></td>
  <td><a href='?srok=$srok&sort=startwork'>startwork</a></td>
  <td><a href='?srok=$srok&sort=finishwork'>stopwork</a></td>
   <td>iduber</td>
   <td>idbolt</td>
   <td>iduklon1</td>
   <td>iduklon2</td>
   <td>txt2</td>
   <td>txt3</td>
   <td>txt4</td>
   <td>tel2</td>
   <td>emailuber</td>
   <td>emailbolt</td>
   <td>txt1</td>
  
</tr>\n";
$i=0;
while ($row = mysqli_fetch_row($result)) {
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
   <td>".$row[11]."</td>
   <td>".$row[12]."</td>
   <td>".$row[13]."</td>
   <td>".$row[14]."</td>
   <td>".$row[15]."</td>
   <td>".$row[16]."</td>
   <td>".$row[17]."</td>
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




echo "</body></html>";

?>

