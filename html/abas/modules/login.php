<?php
if( !defined( 'ACCESSDOC' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    die( "Hacking attempt!" );
}
//@session_start();


$html_css = <<<HTML
    <link href="assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
HTML;
$html_js = <<<HTML
    <script src="assets/js/authentication/form-2.js"></script>
HTML;
$class_body ="form";
?>

<?php
include_once(ROOT_DIR . '/templates/header.php');









$err = "";

if(isset($_POST['login']) and isset($_POST['password']) and isset($_POST['submit']))

{

    $_POST['login'] = $db->safesql( (string)$_POST['login'] );
    $_POST['password'] = (string)$_POST['password'];

    $data= $db->super_query( "SELECT user_id, user_password, user_login, user_role FROM users WHERE user_login='".$_POST['login']."' LIMIT 1" );


    if($data['user_password'] === md5(md5($_POST['password'])))

    {

        $hash = md5(generateCode(10));



//        if(!@$_POST['not_attach_ip'])
//
//        {
//}
            $insip = ", user_ip='".$_SERVER['REMOTE_ADDR']."'";


        $db->query( "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'" );

        set_cookie("abas_id", $data['user_id'], 360);

        set_cookie("abas_hash", $hash, 360);

        $_SESSION['abas_id'] = $data['user_id'];
        $_SESSION['abas_hash'] = $hash;
        $_SESSION['abas_login'] = $data['user_login'];
        $_SESSION['user_role'] = $data['user_role'];

        header("Location: /"); exit();

    }

    else

    {

        $err  =  "<p class=\"\" style=\"color: red\">Вы ввели неправильный логин/пароль</p>";

    }

}

$html = <<<HTML

    

    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <h1 class="">Sign In</h1>
                        <p class="">Log in to your account to continue.</p>
                        
                        {$err}
                        
                        <form class="text-left" method="post" action="/">
                            <div class="form">

                                <div id="username-field" class="field-wrapper input">
                                    <label for="username">USERNAME</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="username" name="login" type="text" class="form-control" placeholder="Username">
                                </div>

                                <div id="password-field" class="field-wrapper input mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label for="password">PASSWORD</label>
                                        <a href="" style="display: none" class="forgot-pass-link">Forgot Password?</a>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper">
                                        <button type="submit" name="submit" class="btn btn-primary" value="submit">Log In</button>
                                    </div>
                                </div>

<input type="hidden" name="not_attach_ip" value="1">

                            </div>
                        </form>

                    </div>                    
                </div>
            </div>
        </div>
    </div>

HTML;
echo $html;

include_once(ROOT_DIR . '/templates/footer.php');
?>

