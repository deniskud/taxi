<?php


$rip=$_SERVER["REMOTE_ADDR"];
$rusr=$_SERVER["REMOTE_USER"];
$rbrz=$_SERVER["HTTP_USER_AGENT"];
$rprt=$_SERVER["REMOTE_PORT"];


$date=date("d.m.y"); // число.месяц.год 
$time=date("H:i"); // часы:минуты:секунды 
$body=$date.'@'.$time;
$body.=' Somebody wos here!'."\n\n";
$body.='IP: '.$rip." port";
$body.=':'.$rprt."\n";
$body.='browser & OS info: '.$rbrz."\n";
$body.='User: '.$rusr."\n";

// Сохраняем в базу данных 
$f = fopen("vesiters.log", "a+"); 
fwrite($f," \n $body ");
fwrite($f,"\n ---------------------------------------\n"); 
fclose($f); 
$f = fopen("index.html", "r");
while(!feof($f)) print fgets($f);
fclose($f);
die();
?>
