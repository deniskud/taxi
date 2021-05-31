<?php



$glubina=$_POST['menu2'];
$shirina=$_POST['shirina'];
$shirina1=$_POST['shirina1'];
$color=$_POST['color'];
$cena=$glubina*$shirina/1000;
$cenam=$cena*0.35;
$tel=$_POST['clienttel'];
$email=$_POST['clientmail'];
$date=date("d.m.y"); // число.месяц.год 
$time=date("H:i"); // часы:минуты:секунды 
$backurl='http://danke.4okna.com/index.html';  // На какую страничку переходит после отправки письма 
$to='denis.kudriakov@gmail.com';
$headers = 'From: MyLandingPage'."\r\n" .
	'Reply-To: '.$email."\r\n" .
	'X-Mailer: PHP/' . phpversion();
$subject = 'Message from my landing page:';
$body='Somebody ordered'."\n\n";
$body.='Email: '.$email."\n";
$body.='Phone: '.$tel."\n";
$body.='Glubina '.$glubina." shirina".$shirina1."\n";
$body.='Cena ='.$cena.' cena montozha'.$cenam;
$body.="\n".$date.'@'.$time."\n";

// Сохраняем в базу данных 
 $f = fopen("message.txt", "a+"); 
fwrite($f," \n $body "); 
fwrite($f,"\n ---------------"); 
fclose($f); 
 
////////////////////////
$f = fopen("test.txt", "r");
while(!feof($f)) print fgets($f);
fclose($f);
////////////////////////

// временно не отправляем
//if(mail($to, $subject, $body, $headers)) {
//	die();
//} else {
//error log
//  $f = fopen("error.txt", "a+"); 
//  fwrite($f," \n $date@$time Error Message failed"); 
//  fwrite($f,"\n ---------------\n"); 
//  fclose($f); 
 	die();
//}

?>
