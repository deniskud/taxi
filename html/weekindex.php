<?php
$t=0;
$head ="<!DOCTYPE HTML>
<html lang='ru-UA'>
 <head>
  <title>Drivers</title>
<!--
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
-->
  <meta charset='utf-8'>
  <style>
   .t1 {background-color: #cccccc; font-weight: bold; text-align: center;font-family: sans-serif;}  
   .t2 {background-color: #eef5ee; font-weight: normal;font-family: sans-serif;}  
   .bott {background-color: #fbfffb ;position: absolute; top:55px; left: 270px; font-family: sans-serif;text-align: leftt;}
   body {font-family: Arial;}
    .it {font-weight: bold;}
    .it2 {font-weight: normal;}
    .iframe1 {background-color: #f0f0f0; 
              font-family: Arial; 
              position: absolute; 
              leftt: 5px; 
              top: 55px;
              width: 250px;
              height: 90%;
              text-align: left;
             };
  </style>
 
 </head>
<body>
<iframe class='iframe1' id='podrobno' name='podrobno'>тут подробно:</iframe>
";
$head.="<a href=index.php><button onclick='exceller()'>на дневную</button><a>";
$head .=" <form action='' method='get'>";
echo $head;

$sort=$_GET['sort'];
if (!$sort) $sort='id';
//if (!$srok) $srok=7;

$now   = new DateTime;
$clone = new DateTime;        //this doesnot clone so:
$clone->modify( '-7 day' );

$end = $now->format( 'Y-m-d' );
$start = $clone->format( 'Y-m-d' );

echo "\n<input type='date' value='$start' name='start'>\n";//<input type='checkbox' name='bot' value='1'";
echo "\n<input type='date' value='$end' name='end'>\n";
echo "<input type='submit' value='обновить'></form>\n";

$dbh = mysqli_connect("127.0.0.1", "zmey", "kalina", "taxi");
if (!$dbh) {
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

mysqli_query($dbh,"SET NAMES 'utf8'");
//building query
//echo "<br>sort=$sort<br>";
$sql = "SELECT * FROM leliki ORDER BY $sort";// ORDER BY $sort";
//echo $sql;
$result = mysqli_query($dbh,$sql) or die('query error: ' . mysql_error());
// Выводим результаты в html

$count=0;
$i=0;
$counter=0;
$leliks= array(array ());
$row =   array(array());
$itogo = array();
$itogobolt = array();
$itogouklon =array();
$popravki = array();
//$tmp = array();
$unal =array();
//pushing data from DB 2 array
while ($row[$counter] = mysqli_fetch_row($result)) $counter++;
mysqli_free_result($result);
//////////////////////////////////////

/////uber
for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  $sql="SELECT SUM(itogo), SUM(balans), SUM(pro60), SUM(pro40), SUM(gotivka) FROM weekuber WHERE weekuber.iduber='".$row[$tmpcnt][6]."';";
//echo $sql;
  $result = mysqli_query($dbh,$sql);// or die('query error: ' . mysql_error());
  $tmp=mysqli_fetch_row($result);
  $itogo[$tmpcnt]=$tmp[0];
  $balans[$tmpcnt]=$tmp[1];
  $pro60[$tmpcnt]=$tmp[2];
  $pro40[$tmpcnt]=$tmp[3];
  $nal[$tmpcnt]=$tmp[4];
  $unal[$tmpcnt]=$tmp[4];
//  echo $tmpcnt." ".$sql."<br>";
//echo $tmp[2];
//echo ":-----------------------------<br>";

}
mysqli_free_result($result);


///uklon
for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  $sql='SELECT SUM(itogo), SUM(balans), SUM(pro60), SUM(pro40), SUM(gotivka)  FROM weekuklon WHERE weekuklon.pozivnoy="'.$row[$tmpcnt][8].'" ;';
  $result = mysqli_query($dbh,$sql);// or die('query error: ' . mysql_error());
  $tmp=mysqli_fetch_row($result);
  $itogouklon[$tmpcnt]=$tmp[0];

  $balans[$tmpcnt]+=$tmp[1];
  $pro60[$tmpcnt]+=$tmp[2];
  $pro40[$tmpcnt]+=$tmp[3];
  $tmp[4]=-$tmp[4];
  $nal[$tmpcnt]+=$tmp[4];
//  echo $tmpcnt." ".$sql."<br>";
}
mysqli_free_result($result);


////bolt
for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  $sql='SELECT SUM(itogo), SUM(balans), SUM(pro60), SUM(pro40), SUM(gotivka)  FROM weekbolt WHERE weekbolt.telbolt="'.$row[$tmpcnt][7].'" ;';
  $result = mysqli_query($dbh,$sql);// or die('query error: ' . mysql_error());
  $tmp=mysqli_fetch_row($result);
  $itogobolt[$tmpcnt]=$tmp[0];

  $balans[$tmpcnt]+=$tmp[1];
  $pro60[$tmpcnt]+=$tmp[2];
  $pro40[$tmpcnt]+=$tmp[3];
  $nal[$tmpcnt]+=$tmp [4];
//  echo $tmpcnt." ".$sql."<br>";
}
mysqli_free_result($result);


//// naliva
for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  $sql='SELECT SUM(itogo), SUM(balans), SUM(pro60), SUM(pro40), SUM(gotivka)  FROM naliva WHERE idtel="'.$row[$tmpcnt][0].'" ;';
//echo $sql;
//echo "<br>";
//echo "<br>$sql<br>";
  $result = mysqli_query($dbh,$sql);// or die('query error: ' . mysql_error());
  $tmp=mysqli_fetch_row($result);
  $nalik[$tmpcnt]=$tmp[4];
  $balans[$tmpcnt]+=$tmp[1];
  $pro60[$tmpcnt]+=$tmp[2];
  $pro40[$tmpcnt]+=$tmp[3];
  $nal[$tmpcnt]-=$tmp[4];
//  echo $tmpcnt." ".$sql."<br>";
}
mysqli_free_result($result);

///// popravki 
for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  $sql="SELECT SUM(gotivka) FROM popravki WHERE idtel=".$row[$tmpcnt][0].";";
//echo $sql;
  $result = mysqli_query($dbh,$sql);
  if (!$result){
    echo "ошибка запроса в БД<br>";
    exit;
  }
  $data= mysqli_fetch_row($result);
  $popravki[$tmpcnt]=$data[0];
  $balans[$tmpcnt]+=$data[0];

}
//  $sql='SELECT SUM(itogo), SUM(balans), SUM(pro60), SUM(pro40), SUM(gotivka)  FROM naliva WHERE idtel="'.$row[$tmpcnt][0].'" ;';
//echo $sql;
//echo "<br>";
//echo "<br>$sql<br>";

/*

//echo $sql;
if ($data[0]){
  $result = mysqli_query($dbh,$sql);// or die('query error: ' . mysql_error());
  $tmp=mysqli_fetch_row($result);
  $nalik[$tmpcnt]=$tmp[4];
  $balans[$tmpcnt]+=$tmp[1];
  $pro60[$tmpcnt]+=$tmp[2];
  $pro40[$tmpcnt]+=$tmp[3];
  $nal[$tmpcnt]-=$tmp[4];
*/

//  echo $tmpcnt." ".$sql."<br>";

mysqli_free_result($result);




//////////////////////////create table:
echo "<div class=bott> <table id='toExcel' class='uitable' border=0>
<tr class=t1>
<td width =30><a href='?sort=id'>id</a></td>
  <td><a href='?sort=txt4'>UU</a></td>
  <td><a href='?sort=txt2'>номер</a></td>
  <td width =120><a href='?srok=$srok&sort=name'>name</a></td>
  <td width =170><a href='?srok=$srok&sort=fam'>fam</a></td>
  <td width =90px> Отчет</td>

  <td width=65px>Uber</td>
  <td width=65px>Bolt</td>
  <td width=65px>Uklon</td>
  <td width=65px>Pyka</td>
  <td width=90px>Итого</td>
  <td width=90px>нал всего</td>
  <td width=90px>60</td>
  <td width=90px>баланс</td>
  <td width=90px>Поправка</td>
</tr>\n";
/////////

for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  $tmp=$row[$tmpcnt][4];

  if ($bf > 0) {
   $tmp="<font size='-3' color='#8fff8f'>".$tmp."</font>";
  }
  else {
   $tmp="<font size='-2' color='#000000'>".$tmp."</font>";
  }
  $i++;                      //
  $cla='';                   // учет четности для вывода строк
  if ( round($i/2) == ($i/2) ) $cla="class=t2";//
  $itogtmp=$itogo[$tmpcnt]+$itogouklon[$tmpcnt]+$itogobolt[$tmpcnt]+$nalik[$tmpcnt];
  $idl= $row[$tmpcnt][0];
  $nomer=$row[$tmpcnt][11];
  $otchet= (-$unal[$tmpcnt])+$itogobolt[$tmpcnt]+$itogouklon[$tmpcnt]+$nalik[$tmpcnt];

  echo "<tr $cla  onmouseout=\"this.style='font-weight: normal;'\" onmouseover=\"this.style='font-weight: bold;'\" >\n";
  ++$count;
  echo "  <td>$idl</td>
  <td>".$row[$tmpcnt][12]."</td> 
  <td>".$row[$tmpcnt][11]."</td> 
  <td align=right><a target='podrobno' href='weekpodrobno.php?uid=$idl'>".$row[$tmpcnt][1]."</a></td>
  <td align=left><a target='podrobno' href='weekpodrobno.php?uid=$idl'>".$row[$tmpcnt][2]."</a></td>

  <td>".number_format($otchet, 0, ',', ' ')."</td>";

  echo " <td>".number_format($itogo[$tmpcnt], 0, ',', ' ')."</td>
  <td>".number_format($itogobolt[$tmpcnt], 0, ',', ' ')."</td>
  <td>".number_format($itogouklon[$tmpcnt], 0, ',', ' ')."</td>
  <td>$nalik[$tmpcnt]</td>
  <td><font color=#222288><b>".number_format($itogtmp, 0, ',', ' ')."</b></font></td>
  <td>".number_format(-$nal[$tmpcnt], 0, ',', ' ')."</td>
  <td>".number_format($pro60[$tmpcnt], 0, ',', ' ')."</td>
<!--
  <td>".number_format($pro40[$tmpcnt], 0, ',', ' ')."</td>
-->
  <td><font color=#";
  if ($balans[$tmpcnt]<0) echo "ff"; else echo "00";
  echo "0000>".number_format($balans[$tmpcnt], 0, ',', ' ')."</font></td>
  <td>".$popravki[$tmpcnt]."</td>";

  echo "\n</tr>\n";
}
echo "</table><br>\n";
////////////// end table

$sumuber=0; //itogo
$sumuklon=0;
$sumbolt=0;
for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  $sumuber+=$itogo[$tmpcnt];
  $sumuklon+=$itogouklon[$tmpcnt];
  $sumbolt+=$itogobolt[$tmpcnt];
}
echo "Всего по Uber : <b>".number_format($sumuber, 0, ',', ' ')."</b><br>";

echo "Всего по Bolt : <b>".number_format($sumbolt, 0, ',', ' ')."</b><br>";
echo "Всего по Uklon : <b>".number_format($sumuklon, 0, ',', ' ')."</b><br>";

$sumvsego=$sumuklon+$sumbolt+$sumuber;

echo "Итого за период : <font color=#222288><b>".number_format($sumvsego, 0, ',', ' ')."</b></font><br>";

// Освобождаем память от результата
mysqli_free_result($result);
echo "\n<hr width=75% align=left>\n";
//echo($count." records.\nall ok\n");



echo "
<form method='GET' action='zmey/addcoment.php' target='podrobno'>
  <select name='id'>\n";
for ($tmpcnt=0;$tmpcnt<$counter;$tmpcnt++){
  echo "    <option value='";
  echo $row[$tmpcnt][0];
  echo "'>";
  echo $row[$tmpcnt][1];
  echo " ";
  echo $row[$tmpcnt][2];
  echo "</option>\n";
}
echo "  </select>
  <input type='text' size='4' id='korr' name='korr' onfocus=\"if (this.value=='0.0') {this.value='';}\" onblur=\"if (this.value==''){this.value='0.0';}\" value='0.0' onclick='return true;'>
  <input type='text' size='70'id='komm' name='komm' onfocus=\"if (this.value=='комментарий') {this.value='';}\" onblur=\"if (this.value==''){this.value='комментарий';}\" value='комментарий' onclick='return true;'>
  <input type='date' id='datec' name='datec' value='";
echo date("Y-m-d");
echo "'>
  <input type='submit' value='Добавить в базу!'>
</form>
";


mysqli_close($dbh);

//echo "</body>\n</html>";
//die();
//echo "</body></html>";
?>

<!--
<hr>
  <form method="POST" action="zmey/upload.php" target="podrobno" enctype="multipart/form-data">
    <div>
      <span>Upload a File:</span>
      <input type="file" name="uploadedFile" />
    </div>
      <p><input name="operator" type="radio" value="uber" checked>Uber (.csv)</p>
      <p><input name="operator" type="radio" value="bolt" >Bolt (.csv)</p>
      <p><input name="operator" type="radio" value="uklon" checked>Uklon (.xlsx)</p>
      <p><input name="operator" type="radio" value="cash" >Cash (.xlsx)</p>

      startd date: <input type='date' id='start' name='startp' value='<?php echo date("Y-m-d");?>'>
      end date: <input type='date' id='end' name='endp' value='<?php echo date("Y-m-d");?>'>
    <input type="submit" name="uploadBtn" value="Upload" />
  </form>
</div>
-->
</body></html>