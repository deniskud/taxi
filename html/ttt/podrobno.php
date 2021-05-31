<?php

$t=0;
$head ="<!DOCTYPE HTML>
<html lang='ru-UA'>
 <head>
  <title>podrobno</title>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <style>
   .t1 {background-color: #cccccc; font-weight: normal; text-align: center;font-family: sans-serif;}  
   .t2 {background-color: #f2fff2; font-weight: normal;font-family: sans-serif;}  
   table {font-family: sans-serif;text-align: right;}
   body {font-family: Arial;}
  </style> 
 </head>
<body>
";
//$head .=" <form action='' method='get'>";
echo $head;

$poezdall=0;
$uid=$_GET['uid'];
if (!$uid) $uid='1';

$start=$_GET['stime'];
$end=$_GET['dtime'];
//&stime=$start&dtime=$end

$dbh = mysqli_connect("127.0.0.1", "zmey", "kalina", "taxi");
if (!$dbh) {
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

mysqli_query($dbh,"SET NAMES 'utf8'"); //set UTF8
/*
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
*/
mysqli_free_result($result);


$sql = "SELECT * FROM leliki WHERE id=$uid ;";// Select data from leliki;
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);
echo "<b>$data[1]&nbsp$data[2]</b><br><a href='callto:$data[3]'> $data[3]</a><br>\n";
echo "<b><div id='zagolovok'></div></b>";
$uberid=$data[6];
$uklonid=$data[8];
$boltid=$data[7];
$id=$data[0];
mysqli_free_result($result);

$svsego=0;
$sbalans=0;
$snal=0;
$s60=0;
/////////////////////////////uber

$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM uber WHERE iduber='$uberid' AND (start BETWEEN '$start 00:00:00' AND '$end 23:59:59');";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);
echo "<hr width=50% align=left>Статистика по <b>Uber</b>: <br>\nПоездок: $data[0] <br>\n";
$poezdall+=$data[0];


echo "ВСЕГО: ".number_format($data[1], 0, ',', ' ')." <br> 
60%=".number_format($data[4], 0, ',', ' ')."<br>
наличка: ".number_format($data[2], 0, ',', ' ')."<br>\n
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
";
$svsego+=$data[1];
$sbalans+=$data[5];
$snal+=-$data[2];
$s60+=$data[4];

mysqli_free_result($result);

////////////////////////uklon

$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM uklon WHERE pozivnoy='$uklonid' AND (start BETWEEN '$start 00:00:00' AND '$end 23:59:59');";
//echo $sql;
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);
echo "<hr width=50% align=left>Статистика по <b>Uklon</b>: <br>\nПоездок: ".$data[0]." <br>\n";
$poezdall+=$data[0];

echo "ВСЕГО: ".number_format($data[1], 0, ',', ' ')." 
<br> 60%=".number_format($data[4], 0, ',', ' ')."<br>
наличка: ".number_format($data[2], 0, ',', ' ')."<br>\n
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
";
$svsego+=$data[1];
$sbalans+=$data[5];
$snal+=$data[2];
$s60+=$data[4];

mysqli_free_result($result);

////////////////////////////////// bolt

$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM bolt WHERE telbolt='$boltid' AND (start BETWEEN '$start 00:00:00' AND '$end 23:59:59');";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);
echo "<hr width=50% align=left>Статистика по <b>Bolt</b>: <br>\nПоездок: $data[0] <br>\n";
$poezdall+=$data[0];

echo "ВСЕГО: ".number_format($data[1], 0, ',', ' ')." 
<br> 60%=".number_format($data[4], 0, ',', ' ')."<br>
наличка: ".number_format($data[2], 0, ',', ' ')."<br>\n
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
";
$svsego+=$data[1];
$sbalans+=$data[5];
$snal-=$data[2];
$s60+=$data[4];


mysqli_free_result($result);

echo "<hr width=50% align=left>";


/////////////////////////////naliva
mysqli_free_result($result);
$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM naliva WHERE idtel='$id' AND (start BETWEEN '$start 00:00:00' AND '$end 23:59:59');";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);

echo "Статистика <b>'С руки'</b>: <br>\nПоездок: $data[0] <br>\n";
$poezdall+=$data[0];

echo "ВСЕГО: ".number_format($data[1], 0, ',', ' ')." 
<br> 60%=".number_format($data[4], 0, ',', ' ')."<br>
наличка: ".number_format($data[2], 0, ',', ' ')."<br>\n
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
<hr>

";

$svsego+=$data[1];
$sbalans+=$data[5];
$snal+=$data[2];
$s60+=$data[4];


$sql="SELECT SUM(gotivka) FROM popravki WHERE idtel=$id AND (start BETWEEN '$start 00:00:00' AND '$end 23:59:59');";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);
//echo $sql;
if ($data[0]){
  echo "Сумма всех поправок: ";
  echo $data[0];
  $sbalans+=$data[0];
  echo "грн<br>Подробно:<br>";
  $sql="SELECT text, gotivka, start FROM popravki WHERE idtel=$id AND (start BETWEEN '$start 00:00:00' AND '$end 23:59:59');";
  $result = mysqli_query($dbh,$sql);
  if (!$result){
      echo "ошибка запроса в БД<br>";
      exit;
  }
  echo "<font size=-1><i>";
  while ($data= mysqli_fetch_row($result)){
    echo " ";
    echo substr($data[2],0,10);
    echo "<b> ";
    echo $data[1];
    echo "грн</b> ";
    echo $data[0];
    echo "<br>";
  }
  echo "------------------</i></font><br>";
//echo $sql;
}
////////////////////////////


echo "<script>document.getElementById('zagolovok').innerHTML='Итого $poezdall поездок<br>ВСЕГО: ".number_format($svsego, 0, ',', ' ')."<br> 60%=".number_format($s60, 0, ',', ' ')."<br>наличка: ".number_format(-1*($snal), 0, ',', ' ')."<br>Баланс: <font color=#";

if ($sbalans<0) echo "ff0000>";
else echo "000000>";

echo number_format($sbalans, 0, ',', ' ');
echo "</font>'; </script>";


mysqli_close($dbh);
?>

</body></html>
