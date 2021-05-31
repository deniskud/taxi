<?php

date_default_timezone_set('Europe/Kiev');
$glubina=$_POST['menu2'];
$shirina=$_POST['shirina'];
$shirina1=$_POST['shirina1'];
$mycolor=$_POST['mycolor'];
$cena=$glubina*$shirina/1000;
$cenam=$cena*0.35;
$tel=$_POST['clienttel'];
$email=$_POST['clientmail'];
$date=date("d.m.y"); // число.месяц.год 
$time=date("H:i"); // часы:минуты:секунды 
$backurl='../index.html';  // На какую страничку переходит после отправки письма 
$to='denis.kudriakov@gmail.com';
$totel='380673256060@sms.kyivstar.net';
$robotmail='Subscribeinformator';
$replayto='noreplay';

$headers = 'From: '.$robotmail."\r\n" .
	'Reply-To: '.$replayto."\r\n" .
	'X-Mailer: PHP/' . phpversion();
$subject = 'Message from my landing page:';
$body=$date.'@'.$time;
$body.=' Somebody ordered'."\n\n";
$body.='Email: '.$email."\n";
$body.='Phone: '.$tel."\n";
$body.='Color '.$mycolor."\n";
$body.='cena za m '.$glubina."  shirina :".$shirina1."mm\n";
$body.='Summa zakaza ='.$cena.' + cena montozha:'.$cenam;
//
// отправляем SMS на "380673256060" "@sms.kyivstar.net"
// mail($totel, $subject, $body, $headers);
//

if(mail($to, $subject, $body, $headers)) {
// Сохраняем в базу данных 
$f = fopen("messages.log", "a+"); 
fwrite($f," \n $body ");
fwrite($f," \n sended 2:$to"); 
fwrite($f,"\n ---------------"); 
fclose($f); 
$f = fopen("send.html", "r");
while(!feof($f)) print fgets($f);
fclose($f);
die();
} else {
//error log
  $f = fopen("meserror.log", "a+"); 
  fwrite($f," \n $date@$time \nError Message failed\n $body"); 
  fwrite($f,"\n ---------------<br><hr>\n"); 
  fclose($f); 
  $f = fopen("error.html", "r");
  while(!feof($f)) print fgets($f);
  fclose($f);
 	die();
}

?>
