<?PHP


if( !defined( 'ACCESSDOC' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	die( "Hacking attempt!" );
}


function set_cookie($name, $value, $expires) {
    global $config;

    if( $expires ) {

        $expires = time() + ($expires * 86400);

    } else {

        $expires = FALSE;

    }


    setcookie( $name, $value, $expires, "/", NULL, NULL, TRUE );

}

function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";

    $code = "";

    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {

        $code .= $chars[mt_rand(0,$clen)];
    }

    return $code;

}

function check_login()
{
    global $db;
    $result = false;

    if (isset($_COOKIE['abas_id']) and isset($_COOKIE['abas_hash'])) {



        $userdata= $db->super_query( "SELECT *,INET_NTOA(user_ip) FROM users WHERE user_id = '" . intval($_COOKIE['abas_id']) . "' LIMIT 1" );



        //or (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR']) and ($userdata['user_ip'] !== "0"))

        if (($userdata['user_hash'] !== $_COOKIE['abas_hash']) or ($userdata['user_id'] !== $_COOKIE['abas_id'])) {

            set_cookie("abas_id", "", 360);

            set_cookie("abas_hash", "", 360);

            unset($_SESSION['abas_id']);
            unset($_SESSION['abas_hash']);
            unset($_SESSION['abas_login']);
            unset($_SESSION['user_role']);

            $result = false;

        } else {


            $_SESSION['abas_id'] = $userdata['user_id'];
            $_SESSION['abas_login'] = $userdata['user_login'];
            $_SESSION['user_role'] = $userdata['user_role'];

            $result = true;
            //echo "{".$userdata['user_hash']." !== ".$_COOKIE['abashash']."} or {".$userdata['user_id']." !== ".$_COOKIE['abasid']."} or {".$userdata['user_ip']."!==".$_SERVER['REMOTE_ADDR']." and ".$userdata['user_ip']." !== 0}";
        }



    }
    return $result;
}


function logout()
{
    set_cookie("abas_id", "", 360);
    set_cookie("abas_hash", "", 360);
    unset($_SESSION['abas_id']);
    unset($_SESSION['abas_hash']);
    unset($_SESSION['abas_login']);
    unset($_SESSION['user_role']);

}
function abs_strlen($value, $charset ) {

    if( function_exists( 'mb_strlen' ) ) {
        return mb_strlen( $value, $charset );
    } elseif( function_exists( 'iconv_strlen' ) ) {
        return iconv_strlen($value, $charset);
    }

    return strlen($value);
}

function check_name($name) {
    global $lang, $db, $config;

    $stop = '';

    if (abs_strlen($name, $config['charset']) > 40 OR abs_strlen(trim($name), $config['charset']) < 3) {
        $stop .= $lang['reg_err_3'];
    }

    if (preg_match("/[\||\'|\<|\>|\[|\]|\%|\"|\!|\?|\$|\@|\#|\/|\\\|\&\~\*\{\}\+]/",$name)) {
        $stop .= $lang['reg_err_4'];
    }

    if (strpos( strtolower ($name) , '.php' ) !== false) {
        $stop .= $lang['reg_err_4'];
    }


    if (!$stop) {

        if( function_exists('mb_strtolower') ) {
            $name = trim(mb_strtolower($name, $config['charset']));
        } else {
            $name = trim(strtolower( $name ));
        }



        $db->query ("SELECT user_login FROM users WHERE  user_login = '{$name}'");

        if ($db->num_rows() > 0) {
            $stop .= $lang['reg_err_20'];
        }
    }

    if (!$stop) return false; else return $stop;
}



?>