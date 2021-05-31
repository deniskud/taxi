<?php
if( !defined( 'ACCESSDOC' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    die( "Hacking attempt!" );
}




if($user_role == '4' or $user_role == '3' or $user_role == '2' or $user_role == '1') { //доступ для овнера,компании,админа






    if($_POST['activreg']=='1') { //сохранить
        $stop ='';


        if($user_role != '4') {
            $phone = $db->safesql(htmlspecialchars(strip_tags(trim($_POST['phone'])), ENT_QUOTES, $config['charset']));
            $adress = $db->safesql(htmlspecialchars(strip_tags(trim($_POST['adress'])), ENT_QUOTES, $config['charset']));
            $text = $db->safesql(htmlspecialchars(strip_tags(trim($_POST['text'])), ENT_QUOTES, $config['charset']));


            $name = $db->safesql(htmlspecialchars(strip_tags(trim($_POST['name'])), ENT_QUOTES, $config['charset']));
            if (!$name) {
                $stop .= $lang['reg_err_24'];
            }


            $not_allow_symbol = array("\x22", "\x60", "\t", '\n', '\r', "\n", "\r", '\\', ",", "/", "#", ";", ":", "~", "[", "]", "{", "}", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', "'", " ", "&");
            $email = $db->safesql(trim(str_replace($not_allow_symbol, '', strip_tags(stripslashes($_POST['email'])))));


            if (!empty($email)) {
                if (strlen($email) > 40 OR @count(explode("@", $email)) != 2) $stop .= $lang['reg_err_6'];
            }


        }

              if($_POST['login']) {
                  $login = strtr($_POST['login'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES, $config['charset'])));
                  $login = trim($login, chr(0xC2) . chr(0xA0));
                  $login = preg_replace('#\s+#i', ' ', $login);
                  $login = $db->safesql(htmlspecialchars(trim($login), ENT_QUOTES, $config['charset']));
                  $stop .= check_name($login);
              }


        $password1 = $_POST['pass'];
        $password2 = $_POST['pass2'];

        if($password1) {

            if ($password1 != $password2) $stop .= $lang['reg_err_1'];
            if (strlen($password1) < 6) $stop .= $lang['reg_err_2'];
            if (strlen($password1) > 72) $stop .= $lang['reg_err_2'];
        }





            if(!$stop) {
                if ($user_role == '4') {
                    $user = $db->super_query("SELECT * FROM users as us WHERE  us.user_id = '{$user_id}' and us.user_role='4'");
                }
                if ($user_role == '3') {
                    $user = $db->super_query("SELECT * FROM users as us, companies as com WHERE  us.user_id = '{$user_id}' and us.user_role='3' and us.user_id = com.user_id");
                }
                if ($user_role == '2') {
                    $user = $db->super_query("SELECT * FROM users as us, admins as adm WHERE  us.user_id = '{$user_id}' and us.user_role='2' and us.user_id = adm.user_id");
                }
                if ($user_role == '1') {
                    $user = $db->super_query("SELECT * FROM users as us, opers as op WHERE  us.user_id = '{$user_id}' and us.user_role='1' and us.user_id = op.user_id");
                }


                if($user['user_id']>0) {

                    if($password1) {
                        $password = md5(md5($password1));
                        $pasup = "user_password='" . $password . "',";
                    }


                    $db->query("UPDATE users SET  {$pasup}  status='" . $status . "' WHERE user_id ='{$user['user_id']}'");


                    if ($user_role == '3') {
                        $db->query("UPDATE companies SET  name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "' WHERE user_id ='{$user['user_id']}'");
                    }
                    if ($user_role == '2') {
                        $db->query("UPDATE admins SET  name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "' WHERE user_id ='{$user['user_id']}'");
                    }
                    if ($user_role == '1') {
                        $db->query("UPDATE opers SET  name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "' WHERE user_id ='{$user['user_id']}'");
                    }

                    $add = true;

                    $addmsg = "<li>Сохранено</li>";
                }else{
                    $stop .= '<li>Не прошёл проверку!</li>';
                }
            }





        if ($stop) {

            $err = <<<HTML
<div class="col-md-12"> 
<div class="alert alert-danger mb-4" role="alert"> 
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
<line x1="18" y1="6" x2="6" y2="18"></line>
<line x1="6" y1="6" x2="18" y2="18"></line>
</svg>
</button> <ul><li><strong>Error!</strong></li> {$stop}</ul>
</div></div>
HTML;

        }

        if ($add) {
            $err = <<<HTML
<div class="col-md-12"> 
<div class="alert alert-success mb-4" role="alert"> 
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
<line x1="18" y1="6" x2="6" y2="18"></line>
<line x1="6" y1="6" x2="18" y2="18"></line>
</svg>
</button> <ul><li><strong>Success!</strong></li> {$addmsg}</ul>
</div></div>
HTML;

        }

    }




    if($user_role == '4' or $user_role == '3' or $user_role == '2' or $user_role == '1'){



       
            $stop='';
            if ($user_role == '4') {
                $user = $db->super_query("SELECT * FROM users as us WHERE  us.user_id = '{$user_id}' and us.user_role='4'");
            }
            if ($user_role == '3') {
                $user = $db->super_query("SELECT * FROM users as us, companies as com WHERE  us.user_id = '{$user_id}' and us.user_role='3' and us.user_id = com.user_id");
            }
            if ($user_role == '2') {
                $user = $db->super_query("SELECT * FROM users as us, admins as adm WHERE  us.user_id = '{$user_id}' and us.user_role='2' and us.user_id = adm.user_id");
            }
            if ($user_role == '1') {
                $user = $db->super_query("SELECT * FROM users as us, opers as op WHERE  us.user_id = '{$user_id}' and us.user_role='1' and us.user_id = op.user_id");
            }

            


            if($user){

                $phone = htmlspecialchars($user['tel1']);
                $adress = htmlspecialchars($user['addr']);
                $text = htmlspecialchars($user['note']);
                $name = htmlspecialchars($user['name']);
                $email = $user['mail'];
                $login = $user['user_login'];



            }else{
                $stop .='<li>Пользователь не найден!</li>';
            }

            if($stop) {
                $err = <<<HTML
<div class="col-md-12"> 
<div class="alert alert-danger mb-4" role="alert"> 
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
<line x1="18" y1="6" x2="6" y2="18"></line>
<line x1="6" y1="6" x2="18" y2="18"></line>
</svg>
</button> <ul><li><strong>Error!</strong></li>{$stop}</ul>
</div></div>
HTML;
            }


            $ps1 = $lang['reg_pass_edit'];
            $ps2 = $lang['reg_pass2_edit'];
            $pas_req = '';
            $disabled ='disabled';






        $html2 .= <<<HTML
<div class="account-settings-container layout-top-spacing">

                    <div class="account-content">
                        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                            <div class="row">
                            
                            

                            
                            
                            
                            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                   <form id="contact" class="section contact" action="" method="post">
                                   
                                        
                                    
                                        <div class="info">
                                            <h5 class="">{$lang['my_profile']} {$login}</h5>
                                            <div class="row">
                                            
 
                                                
                                                <div class="col-md-11 mx-auto">
                                                    <div class="row">
                                                    
                                                    {$err}

HTML;


        if ($user_role == '4') {$t12 = '12'; } else{$t12 = '6';}


            $html2 .= <<<HTML
                                                        <div class="col-md-{$t12}">
                                                            <div class="form-group">
                                                                <label for="login">{$lang['new_login']}</label>
                                                                <input type="text" class="form-control mb-4" id="login" name="login"  value="">
                                                            </div>
                                                        </div>
                                            
                                               
HTML;

        if ($user_role != '4') {
            $html2 .= <<<HTML
                                                       
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="email">{$lang['reg_email']}</label>
                                                                <input id="email" type="text" class="form-control" name="email"  value="{$email}">
                                                            </div>
                                                        </div>
                                                        
HTML;

        }

        $html2 .= <<<HTML
                                                        
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="pass">{$ps1}</label>
                                                                <input id="pass" type="password" name="pass"  class="form-control" {$pas_req}>
                                                            </div>
                                                        </div>
                                                        
HTML;
        if ($user_role != '4') {

            $html2 .= <<<HTML
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="phone">{$lang['reg_phone']}</label>
                                                                <input type="text" class="form-control mb-4" id="phone" name="phone"  value="{$phone}">
                                                            </div>
                                                        </div>
                                                        
HTML;
        }


        $html2 .= <<<HTML
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                
                                                                <label for="pass2">{$ps2}</label>
                                                                <input id="pass2" type="password" name="pass2"  class="form-control" {$pas_req}>
                                                            </div>
                                                        </div>
                                                        
HTML;

        if ($user_role != '4') {

            $html2 .= <<<HTML
                                                                                            
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="adress">{$lang['reg_adress']}</label>
                                                                <input type="text" class="form-control mb-4" id="adress" name="adress"  value="{$adress}">
                                                            </div>
                                                        </div>
                                                        
HTML;


            $html2 .= <<<HTML
                                                        
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="name">{$lang['reg_name']}</label>
                                                                <input type="text" class="form-control mb-4" id="name" name="name"  value="{$name}" required="">
                                                            </div>
                                                        </div>
                                                        
HTML;


            $html2 .= <<<HTML
                                                        
                                                        <div class="col-md-12 mx-auto">
                                                    <div class="form-group">
                                                        <label for="text">{$lang['reg_text']}</label>
                                                        <textarea class="form-control" id="text" name="text"  rows="10">{$text}</textarea>
                                                    </div>
                                                        </div>
                                                
HTML;
        }


        $html2 .= <<<HTML
                                                
                                                <div class="col-md-12 text-right mb-5">
                                                    <button id="submit" type="submit" class="btn btn-primary">{$lang['save']}</button>
                                                    <input name="activreg" type="hidden" value="1">
                                                </div>
                                                
                                                
                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>                   

HTML;






        $html2 .= <<<HTML
                            </div>
                        </div>
                    </div>


                </div>
HTML;



    }
}