<?php

//$date=date("d.m.y"); // число.месяц.год 
//$time=date("H:i"); // часы:минуты:секунды 

//DB server
$host='localhost'; // имя хоста
$database='4okna'; // имя базы данных, 
$user='4okna'; //user 
$pswd='zeratul'; // пароль
 
$dbh = mysqli_connect($host, $user, $pswd, $database) or die("not connect MySQL.");

$sql="CREATE TABLE kiev (
id INT NOT NULL AUTO_INCREMENT,
dt DATETIME NOT NULL DEFAULT NOW(),
ip VARCHAR(16),
port INT(5),
sran VARCHAR(255),
PRIMARY KEY(id)
);";

    if (mysqli_query($dbh,$sql))
        echo "ok!";
    else
        echo "error!";

echo("all ok\n");
mysqli_close($dbh);


#$body=$date.'@'.$time;
#$body.=' Somebody wos here!'."\n\n";
#$body.='IP: '.$rip." port";
#$body.=':'.$rprt."\n";
#$body.='browser & OS info: '.$rbrz."\n";
#$body.='User: '.$rusr."\n";
// Сохраняем в базу данных 
#$f = fopen("vesiters.log", "a+"); 

#fwrite($f," \n $body ");
#fwrite($f,"\n ---------------------------------------\n"); 
#fclose($f); 



//$f = fopen("index1.html", "r");
//while(!feof($f)) print fgets($f);
//fclose($f);



die();
?>

