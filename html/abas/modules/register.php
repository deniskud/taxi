<?php

// Страница регситрации нового пользователя


# Соединямся с БД





if(isset($_POST['submit']))

{

    $err = array();


    # проверям логин

    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))

    {

        $err[] = "Логин может состоять только из букв английского алфавита и цифр";

    }



    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)

    {

        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";

    }



    # проверяем, не сущестует ли пользователя с таким именем
	$_POST['login'] = $db->safesql( (string)$_POST['login'] );



	$query = $db->super_query( "SELECT COUNT(*) as count FROM users WHERE user_login='".$_POST['login']."'" );


    if($query['count'])

    {

        $err[] = "Пользователь с таким логином уже существует в базе данных";

    }



    # Если нет ошибок, то добавляем в БД нового пользователя

    if(count($err) == 0)

    {


        $login = trim($_POST['login']);



        # Убераем лишние пробелы и делаем двойное шифрование

        $password = md5(md5(trim($_POST['password'])));





		$db->query( "INSERT INTO users SET user_login='".$login."', user_password='".$password."'" );

        header("Location: /"); exit();

    }

    else

    {

        print "<b>При регистрации произошли следующие ошибки:</b><br>";

        foreach($err AS $error)

        {

            print $error."<br>";

        }

    }

}

?>
<form method="POST">

	Логин <input name="login" type="text"><br>

	Пароль <input name="password" type="password"><br>

	<input name="submit" type="submit" value="Зарегистрироваться">

</form>
