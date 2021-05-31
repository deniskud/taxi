<?php
  if (isset($_GET['id']))   $id=$_GET['id'];
  if (isset($_GET['komm'])) $komm=$_GET['komm'];
  if (isset($_GET['korr'])) $korr=$_GET['korr'];
  if (isset($_GET['datec'])) $date=$_GET['datec'];


if ($korr!='0.0') {
  $sql="INSERT INTO popravki (idtel, text, gotivka, start) VALUES ('$id', '$komm', '$korr', '$date');";
  $dbh = mysqli_connect("127.0.0.1", "zmey", "kalina", "taxi");
  if (!$dbh) {
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
  }
  mysqli_query($dbh,"SET NAMES 'utf8'");
  $result = mysqli_query($dbh,$sql);
  mysqli_close($dbh);
  if ($result) {
    echo "Комментарий успешно добален";
    exit(0);
  }
}
echo "что-то пошло не так... Ничего в базу не добавилось!"


?>