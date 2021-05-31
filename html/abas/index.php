<?php
@ob_start ();
@ob_implicit_flush (0);
@session_start();

@error_reporting ( E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );

define ( 'ACCESSDOC', true );
define ( 'ROOT_DIR', dirname ( __FILE__ ) );
define ( 'MODULES_DIR', ROOT_DIR . '/modules' );

$selected_language = "ru";

require_once (ROOT_DIR . '/language/' . $selected_language . '/adminpanel.lng');
require_once (ROOT_DIR . '/libs/mysql.php');
require_once (ROOT_DIR . '/data/config.php');
require_once (ROOT_DIR . '/data/dbconfig.php');
require_once (ROOT_DIR . '/functions.php');


if( isset( $_POST['action'] ) ) $action = $_POST['action'];
elseif( isset( $_GET['action'] ) ) $action = $_GET['action'];
else $action = '';

if( isset( $_POST['mod'] ) ) $mod = $_POST['mod'];
elseif( isset( $_GET['mod'] ) ) $mod = $_GET['mod'];
else $mod = '';

if( isset( $_POST['idpr'] ) ) $idpr = $_POST['idpr'];
elseif( isset( $_GET['idpr'] ) ) $idpr = $_GET['idpr'];
else $idpr = 0;
//$_SESSION['abas_login']= '555555555';
//echo $_SESSION['abas_login'];

if(check_login()){
    $is_loged_in = true;

    $user_id = $_SESSION['abas_id'];
    $user_login = $_SESSION['abas_login'];
    $user_role = $_SESSION['user_role'];

    $users_access = $db->super_query("SELECT * FROM users_access WHERE  user_id='{$user_id}'");
}

if($user_role=='4'){
    $lvluser = 'OWNER';
}elseif($user_role=='3'){
    $lvluser = 'COMPANY';
}elseif($user_role=='2'){
    $lvluser = 'ADMIN';
}elseif($user_role=='1'){
    $lvluser = 'OPERATOR';
}else{
    $lvluser = 'НИКТО';
}


if($action=='reg'){
    include_once(ROOT_DIR . '/modules/register.php');
    exit();
}



if(!$is_loged_in) {

    include_once(ROOT_DIR . '/modules/login.php');

    exit();
}else{


        include_once(ROOT_DIR . '/modules/admin.php');

}







if ($action == 'logout') {
    logout();
    header('Location: /');
}



?>



