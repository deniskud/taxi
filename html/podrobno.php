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


$uid=$_GET['uid'];
if (!$uid) $uid='1';


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
echo "<b>$data[1]&nbsp$data[2]</b>&nbsp<a href='callto:$data[3]'> $data[3]</a>&nbsp<FONT SIZE=-1> работает с $data[4]</FONT><br>\n";
$uberid=$data[6];
$uklonid=$data[8];
$boltid=$data[7];
mysqli_free_result($result);


/////////////////////////////uber

$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM uber WHERE iduber='$uberid';";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);
echo "<hr width=50% align=left>Статистика по <b>Uber</b>: <br>\nПоездок: $data[0] <br>\n
наличка: ".number_format($data[2], 0, ',', ' ')."<br>\n
ВСЕГО: ".number_format($data[1], 0, ',', ' ')." (<b>".number_format($data[3], 2, ',', ' ')."</b> + ".number_format($data[4], 2, ',', ' ').")<br>
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
<hr align='left' width='25%'>
";
mysqli_free_result($result);

/////////////////////////////uklon
$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM uklon WHERE pozivnoy='$uklonid';";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);
echo "Статистика по <b>Uklon</b>: <br>\nПоездок: $data[0] <br>\n
наличка: ".number_format(-$data[2], 0, ',', ' ')."<br>\n
ВСЕГО: ".number_format($data[1], 0, ',', ' ')." (<b>".number_format($data[3], 2, ',', ' ')."</b> + ".number_format($data[4], 2, ',', ' ').")<br>
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
<hr align='left' width='25%'>
";
mysqli_free_result($result);

/////////////////////////////bolt
$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM bolt WHERE telbolt='$boltid';";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);

echo "Статистика по <b>Bolt</b>: <br>\nПоездок: $data[0] <br>\n
наличка: ".number_format($data[2], 0, ',', ' ')."<br>\n
ВСЕГО: ".number_format($data[1], 0, ',', ' ')." (<b>".number_format($data[3], 2, ',', ' ')."</b> + ".number_format($data[4], 2, ',', ' ').")<br>
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
<hr align='left' width='25%'>";


/////////////////////////////naliva
mysqli_free_result($result);
$sql="SELECT SUM(poezdok), SUM(itogo), SUM(gotivka), SUM(pro40),SUM(pro60),SUM(balans) FROM naliva WHERE idtel='$boltid';";
$result = mysqli_query($dbh,$sql);
if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
}
$data= mysqli_fetch_row($result);

echo "Статистика по <b>Налу</b>: <br>\nПоездок: $data[0] <br>\n
наличка: ".number_format(-$data[2], 0, ',', ' ')."<br>\n
ВСЕГО: ".number_format($data[1], 0, ',', ' ')." (<b>".number_format($data[3], 2, ',', ' ')."</b> + ".number_format($data[4], 2, ',', ' ').")<br>
Баланс: <font color=#";
if ($data[5]<0) echo "ff0000>";
else echo "000000>";
echo number_format($data[5], 0, ',', ' ');
echo "</font>
<hr align='left' width='25%'>
";


////////////////////////////


 


//echo "</body></html>";


//die();
?>

</body></html>
