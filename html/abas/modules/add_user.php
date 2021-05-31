<?php
if( !defined( 'ACCESSDOC' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    die( "Hacking attempt!" );
}




?>

<?php


if($user_role == '4' or $user_role == '3' or $user_role == '2') { //доступ для овнера,компании,админа

    $idu = intval($_GET['idu']);

    if($mod=='company' and $user_role == '4'){ //доступ только овнер
        $titleh5 = $lang['reg_status'];//Статус предприятия
        $cardcomp = $lang['reg_cart'];//Карточка предприятия
        $role = '3';
        $addmsg = "<li>".$lang['reg_err_25']." ".$lang['reg_err_28']."</li>";
        $tablname = $lang['listadin_titl'];
    };

    if($mod=='admin' and $user_role == '3'){ //доступ только компания
        $titleh5 = $lang['reg_status_adm'];//Статус Администратора
        $cardcomp = $lang['reg_cart_adm'];//Карточка Администратора
        $role = '2';
        $addmsg = "<li>".$lang['reg_err_29']." ".$lang['reg_err_31']."</li>";
        $tablname = $lang['listoper_titl'];
    };

    if($mod=='operator' and $user_role == '2'){ //доступ только админ
        $titleh5 = $lang['reg_status_oper'];//Статус Администратора
        $cardcomp = $lang['reg_cart_oper'];//Карточка Администратора
        $role = '1';
        $addmsg = "<li>".$lang['reg_err_30']." ".$lang['reg_err_31']."</li>";

    };


if($_POST['activreg']=='1') {
    $stop ='';

    $status = trim($_POST['status']);
    if($status=='1'){$stv = 'checked';}

    $phone = $db->safesql( htmlspecialchars(strip_tags( trim( $_POST['phone'] ) ), ENT_QUOTES, $config['charset'] ) );
    $adress = $db->safesql( htmlspecialchars(strip_tags( trim( $_POST['adress'] ) ), ENT_QUOTES, $config['charset'] ) );
    $text = $db->safesql( htmlspecialchars(strip_tags( trim( $_POST['text'] ) ), ENT_QUOTES, $config['charset'] ) );


    $name = $db->safesql(htmlspecialchars(strip_tags(trim($_POST['name'])), ENT_QUOTES, $config['charset']));
    if(!$name) {
        $stop .= $lang['reg_err_24'];
    }


    $not_allow_symbol = array ("\x22", "\x60", "\t", '\n', '\r', "\n", "\r", '\\', ",", "/", "#", ";", ":", "~", "[", "]", "{", "}", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', "'", " ", "&" );
    $email = $db->safesql(trim( str_replace( $not_allow_symbol, '', strip_tags( stripslashes( $_POST['email'] ) ) ) ) );

    //if( empty( $email ) OR strlen( $email ) > 40 OR @count(explode("@", $email)) != 2) $stop .= $lang['reg_err_6'];

    if(!empty($email)) {
        if (strlen($email) > 40 OR @count(explode("@", $email)) != 2) $stop .= $lang['reg_err_6'];
    }



    if(empty($idu)) {

        $login = strtr($_POST['login'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES, $config['charset'])));
        $login = trim($login, chr(0xC2) . chr(0xA0));
        $login = preg_replace('#\s+#i', ' ', $login);
        $login = $db->safesql(htmlspecialchars(trim($login), ENT_QUOTES, $config['charset']));
        $stop .= check_name($login);
    }


        $password1 = $_POST['pass'];
        $password2 = $_POST['pass2'];

        if(!empty($idu) and $password1) {

            if ($password1 != $password2) $stop .= $lang['reg_err_1'];
            if (strlen($password1) < 6) $stop .= $lang['reg_err_2'];
            if (strlen($password1) > 72) $stop .= $lang['reg_err_2'];
        }
        if(empty($idu)) {
            if ($password1 != $password2) $stop .= $lang['reg_err_1'];
            if (strlen($password1) < 6) $stop .= $lang['reg_err_2'];
            if (strlen($password1) > 72) $stop .= $lang['reg_err_2'];
        }


    if(!empty($idu)>0) { //если был передан то обновляем

        if(!$stop) {

            if ($mod == 'company') {
                $user = $db->super_query("SELECT * FROM users as us, companies as com WHERE  us.user_id = '{$idu}' and us.user_role='3' and us.user_id = com.user_id and com.owner_id='{$user_id}'");
            }
            if ($mod == 'admin') {
                $user = $db->super_query("SELECT * FROM users as us, admins as adm WHERE  us.user_id = '{$idu}' and us.user_role='2' and us.user_id = adm.user_id and adm.copmp_id='{$user_id}'");
            }
            if ($mod == 'operator') {
                $user = $db->super_query("SELECT * FROM users as us, opers as op WHERE  us.user_id = '{$idu}' and us.user_role='1' and us.user_id = op.user_id and op.adm_id='{$user_id}'");
            }


  if($user['user_id']>0) {

        if($password1) {
            $password = md5(md5($password1));
            $pasup = "user_password='" . $password . "',";
        }


      $db->query("UPDATE users SET  {$pasup}  status='" . $status . "' WHERE user_id ='{$user['user_id']}'");


      if ($mod == 'company') {
          $db->query("UPDATE companies SET  name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "' WHERE user_id ='{$user['user_id']}'");
      }
      if ($mod == 'admin') {
          $db->query("UPDATE admins SET  name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "' WHERE user_id ='{$user['user_id']}'");
      }
      if ($mod == 'operator') {
          $db->query("UPDATE opers SET  name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "' WHERE user_id ='{$user['user_id']}'");
      }

      $add = true;

      $addmsg = "<li>Сохранено</li>";
  }else{
      $stop .= '<li>Не прошёл проверку!</li>';
  }
}



    }else { //или добавляем
        if (!$stop) {
            $password = md5(md5($password1));

            $db->query("INSERT INTO users SET user_login='" . $login . "', user_password='" . $password . "', user_role='" . $role . "', status='" . $status . "'");
            $iduser = $db->insert_id();

            if ($iduser > 0) {
                $db->query("INSERT INTO users_access SET user_id='" . $iduser . "'");
            }
            if ($mod == 'company' and $iduser > 0) {
                $db->query("INSERT INTO companies SET user_id='" . $iduser . "', owner_id='" . $user_id . "', name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "'");
            }
            if ($mod == 'admin' and $iduser > 0) {
                $db->query("INSERT INTO admins SET user_id='" . $iduser . "', copmp_id='" . $user_id . "', name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "'");
            }
            if ($mod == 'operator' and $iduser > 0) {
                $db->query("INSERT INTO opers SET user_id='" . $iduser . "', adm_id='" . $user_id . "', name='" . $name . "', addr='" . $adress . "', tel1='" . $phone . "', mail='" . $email . "', note='" . $text . "'");
            }

            $add = true;

            $phone = '';
            $adress = '';
            $text = '';
            $name = '';
            $email = '';
            $login = '';
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
    if(($mod=='company' and $user_role == '4') or ($mod=='admin' and $user_role == '3') or ($mod=='operator' and $user_role == '2')){



if(!empty($idu)>0) {
    $stop='';
    if ($mod == 'company') {
        $user = $db->super_query("SELECT * FROM users as us, companies as com WHERE  us.user_id = '{$idu}' and us.user_role='3' and us.user_id = com.user_id and com.owner_id='{$user_id}'");
    }
    if ($mod == 'admin') {
        $user = $db->super_query("SELECT * FROM users as us, admins as adm WHERE  us.user_id = '{$idu}' and us.user_role='2' and us.user_id = adm.user_id and adm.copmp_id='{$user_id}'");
    }
    if ($mod == 'operator') {
        $user = $db->super_query("SELECT * FROM users as us, opers as op WHERE  us.user_id = '{$idu}' and us.user_role='1' and us.user_id = op.user_id and op.adm_id='{$user_id}'");
    }
if($user){





    $phone = htmlspecialchars($user['tel1']);
    $adress = htmlspecialchars($user['addr']);
    $text = htmlspecialchars($user['note']);
    $name = htmlspecialchars($user['name']);
    $email = $user['mail'];
    $login = $user['user_login'];

    if($user['status']=='1'){
        $stv ='checked';
    }else{
        $stv ='';
    }

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

    $but= $lang['reg_submit_edit'];
    $ps1 = $lang['reg_pass_edit'];
    $ps2 = $lang['reg_pass2_edit'];
    $pas_req = '';
    $disabled ='disabled';
}else{
   $but= $lang['reg_submit'];
   $ps1 = $lang['reg_pass'];
    $ps2 = $lang['reg_pass2'];
    $pas_req = 'required=""';
}

        $html2 .= <<<HTML
<div class="account-settings-container layout-top-spacing">

                    <div class="account-content">
                        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                            <div class="row">
                            
                            

                            
                            
                            
                            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                   <form id="contact" class="section contact" action="" method="post">
                                     <div class="info">
                                            <h5 class="">{$titleh5}</h5>
                                            <div class="row">
                                                <div class="col-md-11 mx-auto">
                                                    <div class="form-group text-right">
                                                         <label class="switch s-outline s-outline-success  mb-4 mr-2">
                                                <input type="checkbox" name="status" id="status" value="1" {$stv} >
                                                <span class="slider round"></span>
                                            </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    
                                        <div class="info">
                                            <h5 class="">{$cardcomp}</h5>
                                            <div class="row">
                                            
 
                                                
                                                <div class="col-md-11 mx-auto">
                                                    <div class="row">
                                                    
                                                    {$err}

                                            
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="login">{$lang['reg_login']}</label>
                                                                <input type="text" class="form-control mb-4" id="login" name="login"  value="{$login}" required="" {$disabled}>
                                                            </div>
                                                        </div>
                                            
                                               
                                                        
                                                       
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="email">{$lang['reg_email']}</label>
                                                                <input id="email" type="text" class="form-control" name="email"  value="{$email}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="pass">{$ps1}</label>
                                                                <input id="pass" type="password" name="pass"  class="form-control" {$pas_req}>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="phone">{$lang['reg_phone']}</label>
                                                                <input type="text" class="form-control mb-4" id="phone" name="phone"  value="{$phone}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                
                                                                <label for="pass2">{$ps2}</label>
                                                                <input id="pass2" type="password" name="pass2"  class="form-control" {$pas_req}>
                                                            </div>
                                                        </div>                                    
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="adress">{$lang['reg_adress']}</label>
                                                                <input type="text" class="form-control mb-4" id="adress" name="adress"  value="{$adress}">
                                                            </div>
                                                        </div>
                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="name">{$lang['reg_name']}</label>
                                                                <input type="text" class="form-control mb-4" id="name" name="name"  value="{$name}" required="">
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                        <div class="col-md-12 mx-auto">
                                                    <div class="form-group">
                                                        <label for="text">{$lang['reg_text']}</label>
                                                        <textarea class="form-control" id="text" name="text"  rows="10">{$text}</textarea>
                                                    </div>
                                                        </div>
                                                
                                                <div class="col-md-12 text-right mb-5">
                                                    <button id="submit" type="submit" class="btn btn-primary">{$but}</button>
                                                    <input name="activreg" type="hidden" value="1">
                                                </div>
                                                
                                                
                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                           

            
                                

HTML;








//Таблица администраторов
if($user and !empty($idu)>0) {


    if ($mod == 'company') {

        $sql_result3 = $db->query("SELECT adm.*, COUNT(prib.admid) as countprib , user_login 
FROM admins as adm 
LEFT JOIN pribors prib ON (prib.admid=adm.user_id)
LEFT JOIN users us ON (us.user_id = adm.user_id) 
WHERE adm.copmp_id='{$user['user_id']}' 
GROUP BY adm.id");

    }
//Таблица операторов
    if ($mod == 'admin') {
        $sql_result = $db->query("SELECT op.*, COUNT(prib.operid) as countprib , user_login 
FROM opers as op 
LEFT JOIN pribors prib ON (prib.operid=op.user_id)
LEFT JOIN users us ON (us.user_id = op.user_id) 
WHERE op.adm_id='{$user['user_id']}' 
GROUP BY op.id" );
    }


    while ($row = $db->get_row($sql_result3)) {
        $conap++;

        if ($row['status '] == '1') {
            $sat = '<span class="badge outline-badge-success">Активен</span>';
        } else {
            $sat = '<span class="badge outline-badge-danger">НЕ активен</span>';
        }

        $htmlres .= <<<HTML
                                            <tr>
                                                <td class="checkbox-column text-center">{$conap}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['user_login']}</td>
                                                <td>{$row['addr']}</td>
                                                <td>{$row['tel1']}</td>
                                                <td>{$row['countprib']}</td>
                                                <td class="text-center td-content">{$sat}</td>
                                            </tr>
HTML;
    }

    $html2 .= <<<HTML
    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>{$tablname}</h4>
                                    </div>
                                </div>
                            </div>
                                                 <!--<div class="col-md-12 text-right mb-5">-->
                                                    <!--<button type="button" class="btn btn-primary mb-2 mr-2" data-toggle="modal" data-target="#adduser">Добавить</button>-->
                                                <!--</div>-->
                                                
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive mb-4">
                                    <table id="list-admin" class="table style-3  table-hover">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column text-center">Id </th>
                                                <th>Имя/Название</th>
                                                <th>Логин</th>
                                                <th>Адрес</th>
                                                <th>Телефон</th>
                                                <th>Кол-во приборов</th> 
                                                <th>Статус</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {$htmlres}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
<div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
HTML;

//таблица приборов

      $butnewpr  = <<<HTML
          <div class="col-md-12 text-right mb-5">                                
           <button type="button" class="btn btn-primary mb-2 mr-2" data-toggle="modal" data-target="#addprib">Назначить прибор</button>
         </div>
HTML;

 if($idu) {

     if ($user_role == '4') {
         $seluserls = "owid = '{$user_id}'";
     }
     if ($user_role == '3') {
         $seluserls = "cmpid = '{$user_id}'";
     }
     if ($user_role == '2') {
         $seluserls = "admid = '{$user_id}'";
     }

     if ($user_role == '1') {
         $seluserls = "operid = '{$user_id}'";
     }

     
     if ($mod == 'company') {
         $listuz = 'cmpid';
     }
     if ($mod == 'admin') {
         $listuz = 'admid';
     }
     if ($mod == 'operator') {
         $listuz = 'operid';
     }



     if ($_POST['addprtouser'] and intval($_POST['op_addprib']) > 0) {  //присваиваем прибор пользователю

         if ($seluserls) {


             if ($mod == 'company') {
                 $db->query("UPDATE pribors SET cmpid = '{$idu}', admid = '0', operid = '0'  WHERE {$seluserls} and  id ='{$_POST['op_addprib']}'");
             }
             if ($mod == 'admin') {
                 $db->query("UPDATE pribors SET admid = '{$idu}', operid = '0'  WHERE {$seluserls} and  id ='{$_POST['op_addprib']}'");
             }
             if ($mod == 'operator') {
                 $db->query("UPDATE pribors SET operid = '{$idu}'  WHERE {$seluserls} and  id ='{$_POST['op_addprib']}'");
             }



         }
     }

 }



    require_once (ROOT_DIR . '/modules/list_pribor.php');



} //end $user  tabl



$html2 .= <<<HTML
                           </div>  </div>
                        </div>
                    </div>


                </div>
HTML;
        if($idu) {


        $sql_sq = "SELECT * FROM pribors WHERE {$seluserls}";
        
        $sql_result = $db->query($sql_sq);

        while ( $row7 = $db->get_row( $sql_result ) ) {

              if($idu == $row7[$listuz] ){
                  $listprib .= '<option value="'.$row7['id'].'" disabled>- '.$row7['serial'].' ('.$row7['loc_name'].')</option>';
              } else{
                  $listprib .= '<option value="'.$row7['id'].'" >+ '.$row7['serial'].' ('.$row7['loc_name'].')</option>';
              }



        }



        $html2 .= <<<HTML
<!-- Modal -->
<div class="modal fade" id="addprib" tabindex="-1" role="dialog" aria-labelledby="addprib" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить прибор</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form method="post" action="/?action=add_user&mod={$mod}&idu={$idu}">
            <div class="modal-body">
                <p class="modal-text">
                <select class="form-control" id="comp" name="op_addprib">
                     <option value="0"></option>
                     {$listprib}
                      </select>
</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Отмена</button>
                <button type="submit" class="btn btn-primary">Добавить</button>
                <input type="hidden" name="addprtouser" value="1"> 
            </div>
            </form>
        </div>
    </div>
</div>
HTML;



        $html2 .= <<<HTML
<!-- Modal -->
<div class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="addprib" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form method="post" action="/?action=add_user&mod={$mod}&idu={$idu}">
            <div class="modal-body">
                <p class="modal-text">
                <select class="form-control" id="comp" name="opcomp">
                     <option value="0"></option>
                     
                      </select>
</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Отмена</button>
                <button type="submit" class="btn btn-primary">Добавить</button>
                <input type="hidden" name="adduser" value="1"> 
            </div>
            </form>
        </div>
    </div>
</div>
HTML;
}
}      }