<?php
if( !defined( 'ACCESSDOC' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    die( "Hacking attempt!" );
}




?>

<?php
$idpr = intval($_GET['idpr']);

if($user_id and !empty($idpr)>0) {


    if ($user_role == '4') {
        $selprib = "pr.owid='{$user_id}'";
        $sqselcop = "owner_id='{$user_id}'";
        $sqseladm = "owner_id='{$user_id}'";
    }
    if ($user_role == '3') {
        $selprib = "pr.cmpid='{$user_id}'";
        $sqseladm = "copmp_id='{$user_id}'";
    }
    if ($user_role == '2') {
        $selprib = "pr.admid='{$user_id}'";
        $sqseloper = "adm_id='{$user_id}'";
    }
    if ($user_role == '1') {
        $selprib = "pr.operid='{$user_id}'";
    }


    if($selprib ){


     //сохрание настроек

        
      if($_POST['activsv']){

          $statuspr = ($_POST['statuspr'] ? 1 : 0);

          $disablefeed = ($_POST['disablefeed'] ? 1 : 0);
          $powerdisin = ($_POST['powerdisin'] ? intval($_POST['powerdisin']) : 0);
          $supplytime = ($_POST['supplytime'] ? intval($_POST['supplytime']) : 0);
          $volume = ($_POST['volume'] ? intval($_POST['volume']) : 0);
          $templimit = ($_POST['templimit'] ? intval($_POST['templimit']) : 0);



          $opaddr = $db->safesql( htmlspecialchars(strip_tags( trim( $_POST['opaddr'] ) ), ENT_QUOTES, $config['charset'] ) );
          $oplcname = $db->safesql( htmlspecialchars(strip_tags( trim( $_POST['oplcname'] ) ), ENT_QUOTES, $config['charset'] ) );

          $not_allow_symbol = array ("\x22", "\x60", "\t", '\n', '\r', "\n", "\r", '\\', ",", "/", "#", ";", ":", "~", "[", "]", "{", "}", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"', "'", " ", "&" );
          $email = $db->safesql(trim( str_replace( $not_allow_symbol, '', strip_tags( stripslashes( $_POST['opemail'] ) ) ) ) );

          if( empty( $email ) OR strlen( $email ) > 40 OR @count(explode("@", $email)) != 2) $stop .= $lang['pribor_err_1'];



          if ($user_role == '4') {
              $idcomp = ($_POST['opcomp'] ? intval($_POST['opcomp']) : 0);
                 if($idcomp>0) {
                     $sql_idcp = $db->super_query("SELECT id,user_id,name FROM companies WHERE user_id='{$idcomp}' and  {$sqselcop}");
                     $sql_idcp = ($sql_idcp['user_id'] ? $sql_idcp['user_id'] : 0);
                 }
          }



          if ($user_role == '3') {
              $idadm = ($_POST['opadmin'] ? intval($_POST['opadmin']) : 0);
              if($idadm>0) {
                  $sql_idad = $db->super_query("SELECT id,user_id,name FROM admins WHERE user_id='{$idadm}' and  {$sqseladm}");
                  $sql_idad = ($sql_idad['user_id'] ? $sql_idad['user_id'] : 0);
              }
          }


          if ($user_role == '2') {
              $idoper = ($_POST['opoper'] ? intval($_POST['opoper']) : 0);
              if($idoper>0) {
                  $sql_idop = $db->super_query("SELECT id,user_id,name FROM opers WHERE user_id='{$idoper}' and  {$sqseloper}");
                  $sql_idop = ($sql_idop['user_id'] ? $sql_idop['user_id'] : 0);
              }
          }

             $sqlupd = array();

          $sqlupd[] ="loc_name ='" . $oplcname . "'"; // имя
          $sqlupd[] ="addr ='" . $opaddr . "'"; // адрес
          $sqlupd[] ="op_email ='" . $email . "'"; // емаил
          $sqlupd[] ="op_status ='" . $statuspr . "'"; //статус прибора
          $sqlupd[] ="op_disablefeed ='" . $disablefeed . "'"; //вкл выкл подачи жидк.
          
          if($users_access['allow_powerdz']=='1'){$sqlupd[] ="op_powerdisin='" . $powerdisin . "'";}  //Сила дезин-ра
          if($users_access['allow_supply']=='1'){$sqlupd[] ="op_supplytime='" . $supplytime . "'";}  //Время подачи
          if($users_access['allow_volume']=='1'){$sqlupd[] ="op_volume='" . $volume . "'";}  //Громкость
          if($users_access['allow_temperature']=='1'){$sqlupd[] ="op_templimit='" . $templimit . "'";}  //придел темп.


        if ($user_role == '4' and ($_POST['hiddenus']!=$sql_idcp)) {
            $sqlupd[] ="cmpid='" . $sql_idcp . "'"; //компания
            $sqlupd[] ="admid='0'"; //админ
            $sqlupd[] ="operid='0'"; //оператор
        }
        if ($user_role == '3' and ($_POST['hiddenus']!=$sql_idad)) {
            $sqlupd[] ="admid='" . $sql_idad . "'"; //админ
            $sqlupd[] ="operid='0'"; //оператор
        }
        if ($user_role == '2' and ($_POST['hiddenus']!=$sql_idop)) {
            $sqlupd[] ="operid='" . $sql_idop . "'"; //оператор
        }



          $sqlupd = implode(",",$sqlupd);

           

          if($sqlupd) {
              $db->query("UPDATE pribors pr SET {$sqlupd} WHERE  id='{$idpr}' and {$selprib}");





              $pribs = $db->super_query("SELECT * FROM pribors as pr WHERE  pr.id='{$idpr}' and {$selprib}");


              if ($user_role != '1') {    //если не оператор обрабатываем права
                   //права текущего переданого юзера (комп. или админа или оператора)
                  $allow_addvideo = ($_POST['allow_addvideo'] ? 1 : 0);
                  $allow_powerdz = ($_POST['allow_powerdz'] ? 1 : 0);
                  $allow_durationdz = ($_POST['allow_durationdz'] ? 1 : 0);
                  $allow_volume = ($_POST['allow_volume'] ? 1 : 0);
                  $allow_temperature = ($_POST['allow_temperature'] ? 1 : 0);
                  $allow_supply = ($_POST['allow_supply'] ? 1 : 0);

                  $accessid = 0;

                  if ($user_role == '4' and $sql_idcp) {   //если юзер текущий компания
                      $accessid = $sql_idcp;
                        $dopsel_ad = array();   //собераем права для админа
                        $dopsel_ad[] = ($allow_addvideo=='1' ? '' : "allow_addvideo='0'");
                        $dopsel_ad[] = ($allow_powerdz=='1' ? '' : "allow_powerdz='" . $allow_powerdz . "'");
                        $dopsel_ad[] = ($allow_durationdz=='1' ? '' : "allow_durationdz='" . $allow_durationdz . "'");
                        $dopsel_ad[] = ($allow_volume=='1' ? '' : "allow_volume='" . $allow_volume . "'");
                        $dopsel_ad[] = ($allow_temperature=='1' ? '' : "allow_temperature='" . $allow_temperature . "'");
                        $dopsel_ad[] = ($allow_supply=='1' ? '' : "allow_supply='" . $allow_supply . "'");
                        $dopsel_ad = array_diff($dopsel_ad, array('', NULL, false));

                      $dopsel_op = array(); //собераем права для оператора
                      $dopsel_op[] = ($allow_addvideo=='1' ? '' : "allow_addvideo='0'");
                      $dopsel_op[] = ($allow_powerdz=='1' ? '' : "allow_powerdz='" . $allow_powerdz . "'");
                      $dopsel_op[] = ($allow_durationdz=='1' ? '' : "allow_durationdz='" . $allow_durationdz . "'");
                      $dopsel_op[] = ($allow_volume=='1' ? '' : "allow_volume='" . $allow_volume . "'");
                      $dopsel_op[] = ($allow_temperature=='1' ? '' : "allow_temperature='" . $allow_temperature . "'");
                      $dopsel_op[] = ($allow_supply=='1' ? '' : "allow_supply='" . $allow_supply . "'");
                      $dopsel_op = array_diff($dopsel_op, array('', NULL, false));
                  }


                  if ($user_role == '3' and $sql_idad) {    //если юзер текущий админ
                      $accessid = $sql_idad;
                      $dopsel_op = array(); //собераем права для оператора
                      $dopsel_op[] = ($allow_addvideo=='1' ? '' : "allow_addvideo='0'");
                      $dopsel_op[] = ($allow_powerdz=='1' ? '' : "allow_powerdz='" . $allow_powerdz . "'");
                      $dopsel_op[] = ($allow_durationdz=='1' ? '' : "allow_durationdz='" . $allow_durationdz . "'");
                      $dopsel_op[] = ($allow_volume=='1' ? '' : "allow_volume='" . $allow_volume . "'");
                      $dopsel_op[] = ($allow_temperature=='1' ? '' : "allow_temperature='" . $allow_temperature . "'");
                      $dopsel_op[] = ($allow_supply=='1' ? '' : "allow_supply='" . $allow_supply . "'");
                      $dopsel_op = array_diff($dopsel_op, array('', NULL, false));
                  }


                  if ($user_role == '2' and $sql_idop) {    //если юзер текущий админ
                      $accessid = $sql_idop;
                  }

                  if ($accessid > 0) {
                      $db->query("UPDATE users_access SET 
allow_addvideo='" . $allow_addvideo . "', 
allow_powerdz='" . $allow_powerdz . "', 
allow_durationdz='" . $allow_durationdz . "', 
allow_volume='" . $allow_volume . "', 
allow_temperature='" . $allow_temperature . "', 
allow_supply='" . $allow_supply . "' 
WHERE  user_id='{$accessid}'");


  if(count($dopsel_ad)>0){
      $dopsel_ad = implode(",",$dopsel_ad);
      $db->query("UPDATE users_access SET {$dopsel_ad} WHERE  user_id='{$pribs['admid']}'");

  }
  if(count($dopsel_op)>0){
      $dopsel_op = implode(",",$dopsel_op);
      $db->query("UPDATE users_access SET {$dopsel_op} WHERE  user_id='{$pribs['operid']}'");
  }

                  }

              }
              $add = true;
              $addmsg = "<li>Сохранено</li>";
          }

          if ($stop) {

              $err .= <<<HTML
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
              $err .= <<<HTML
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



     //-------------------------------------

          //вытаскиваем прибор


        $prib = $db->super_query("SELECT * FROM pribors as pr WHERE  pr.id='{$idpr}' and {$selprib}");

if($prib){






    if($prib['op_disablefeed']=='1'){
        $disablefeed = 'checked';
    }


    


        if($prib['op_disablefeed']=='1'){
            $disablefeed = 'checked';
        }
        if($prib['op_status']=='1'){
            $statuspr = 'checked';
        }





        if($user_role=='4') { //если овнер выводим список комнаний

            $sql_ow = $db->query("SELECT id,user_id,name FROM companies WHERE {$sqselcop}" );
            while ($row5 = $db->get_row($sql_ow)) {
                if($prib['cmpid']==$row5['user_id']) {
                    $compsel .= "<option value='{$row5['user_id']}' selected>{$row5['name']}</option>";
                    $swlacs = $row5['user_id'];
                }else{
                    $compsel .= "<option value='{$row5['user_id']}'>{$row5['name']}</option>";
                }
            }


            $vlsel .= <<<HTML
                     <div class="form-group">
                     <label for="country">Предприятие</label>
                     <select class="form-control" id="comp" name="opcomp">
                     <option value='0'></option>
                           {$compsel}
                      </select>
                      <input type="hidden" name="hiddenus" value="{$swlacs}">
                     </div>
HTML;

        }


        if($user_role=='3') { //если предприятия выводим список админов

            $sql_cop = $db->query("SELECT id,user_id,name FROM admins WHERE {$sqseladm}");
            while ($row4 = $db->get_row($sql_cop)) {
                if($prib['admid']==$row4['user_id']) {
                    $adminsel .= "<option value='{$row4['user_id']}' selected>{$row4['name']}</option>";
                    $swlacs = $row4['user_id'];
                }else{
                    $adminsel .= "<option value='{$row4['user_id']}' >{$row4['name']}</option>";
                }
            }


            $vlsel .= <<<HTML
                    <div class="form-group">
                    <label for="country">Админ.</label>
                    <select class="form-control" id="admin" name="opadmin">
                    <option value='0'></option>
                             {$adminsel}
                     </select>
                     <input type="hidden" name="hiddenus" value="{$swlacs}">
                    </div>
HTML;

        }


    if($user_role=='2') { //если админ выводим список оперпторов

        $sql_op = $db->query("SELECT id,user_id,name FROM opers WHERE {$sqseloper}");
        while ($row5 = $db->get_row($sql_op)) {
            if($prib['operid']==$row5['user_id']) {
                $opersel .= "<option value='{$row5['user_id']}' selected>{$row5['name']}</option>";
                $swlacs = $row5['user_id'];
            }else{
                $opersel .= "<option value='{$row5['user_id']}' >{$row5['name']}</option>";
            }
        }


        $vlsel .= <<<HTML
                    <div class="form-group">
                    <label for="country">Оператор</label>
                    <select class="form-control" id="operator" name="opoper">
                    <option value='0'></option>
                             {$opersel}
                     </select>
                     <input type="hidden" name="hiddenus" value="{$swlacs}">
                    </div>
HTML;

    }




       if($swlacs) {
           $access_user = $db->super_query("SELECT * FROM users_access WHERE  user_id='{$swlacs}'");
       }
    $allow_addvideo2 = ($access_user['allow_addvideo']==1 ? 'checked' : '');
    $allow_powerdz2 = ($access_user['allow_powerdz']==1 ? 'checked' : '');
    $allow_durationdz2 = ($access_user['allow_durationdz']==1 ? 'checked' : '');
    $allow_volume2 = ($access_user['allow_volume']==1 ? 'checked' : '');
    $allow_temperature2 = ($access_user['allow_temperature']==1 ? 'checked' : '');
    $allow_supply2 = ($access_user['allow_supply']==1 ? 'checked' : '');





    $html2 .= <<<HTML
<div class="account-settings-container layout-top-spacing">
<form id="contact" action="/?action=edit_pribor&idpr={$idpr}" method="post">
                    <div class="account-content">
                        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                        
                            <div class="row">
                             {$err}
                         
                            
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                   
                                   <div class="section contact">
                                     <div class="info">
                                            <h5 class="">Прибор №{$prib['serial']}</h5>
                                        <div class="row">
                                        
                                            <div class="col-md-11 mx-auto">
                                                    <div class="form-group text-right">
                                            <label class="switch s-outline s-outline-success  mb-4 mr-2">
                                                <input type="checkbox" name="statuspr" {$statuspr}>
                                                <span class="slider round"></span>
                                            </label>
                                            </div>
                                                    
                                                </div>
                                            </div>
                                     </div>
                                     </div>
                                     
                              </div>
                              
                              
                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget section skill">

                            <div class="widget-heading">
                                <h5 class="">Настройки</h5>
                            </div>

                            <div class="widget-content">
HTML;

                                
if($users_access['allow_powerdz']=='1') {
    $html2 .= <<<HTML
        <div class="col-md-11 mx-auto">
        <p class="skill-name" style="margin-bottom: 50px;">Сила дезин-ра</p>
            <div id="slider1"></div>
            
            <input type="hidden" id="slider1-span" name="powerdisin" value="{$prib['op_powerdisin']}">
        </div>
    <br/>  
HTML;
}else{
    $html2 .= <<<HTML
                                                <div class="col-md-11 mx-auto" >
                                   <p class="skill-name">Сила дезин-ра</p>         
                                <div class="progress br-30">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {$prib['op_powerdisin']}%" aria-valuenow="{$prib['op_powerdisin']}" aria-valuemin="0" aria-valuemax="100"><div class="progress-title"><span>{$prib['op_powerdisin']}%</span> </div></div>
                                </div>
                                </div>
HTML;
}

    if($users_access['allow_supply']=='1') {
        $html2 .= <<<HTML
        <div class="col-md-11 mx-auto">
        <p class="skill-name" style="margin-bottom: 50px;">Время подачи</p>
            <div id="slider2"></div>
            <input type="hidden" id="slider2-span" name="supplytime" value="{$prib['op_supplytime']}">
        </div>
            <br/>
HTML;
    }else{
        $html2 .= <<<HTML
                                       <div class="col-md-11 mx-auto">
                                   <p class="skill-name">Время подачи</p>         
                                <div class="progress br-30">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {$prib['op_supplytime']}%" aria-valuenow="{$prib['op_supplytime']}" aria-valuemin="0" aria-valuemax="100"><div class="progress-title"><span>{$prib['op_supplytime']}%</span> </div></div>
                                </div>
                                </div>
HTML;
    }

    if($users_access['allow_volume']=='1') {
        $html2 .= <<<HTML
        <div class="col-md-11 mx-auto">
        <p class="skill-name" style="margin-bottom: 50px;">Громкость</p>
            <div id="slider3"></div>
            <input type="hidden" id="slider3-span" name="volume" value="{$prib['op_volume']}">
        </div>
            <br/>
HTML;
    }else{
        $html2 .= <<<HTML
                                       <div class="col-md-11 mx-auto">
                                   <p class="skill-name">Громкость</p>         
                                <div class="progress br-30">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {$prib['op_volume']}%" aria-valuenow="{$prib['op_volume']}" aria-valuemin="0" aria-valuemax="100"><div class="progress-title"><span>{$prib['op_volume']}%</span> </div></div>
                                </div>
                                </div>
HTML;
    }


    if($users_access['allow_temperature']=='1') {
        $html2 .= <<<HTML
        <div class="col-md-11 mx-auto">
        <p class="skill-name" style="margin-bottom: 50px;">Температурный придел</p>
            <div id="slider4"></div>
            <input type="hidden" id="slider4-span" name="templimit" value="{$prib['op_templimit']}">
        </div>                       
HTML;
}else{
        $html2 .= <<<HTML
                                        <div class="col-md-11 mx-auto">
                                   <p class="skill-name">Температурный придел</p>         
                                <div class="progress br-30">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {$prib['op_templimit']}%" aria-valuenow="{$prib['op_templimit']}" aria-valuemin="0" aria-valuemax="100"><div class="progress-title"><span>{$prib['op_templimit']}%</span> </div></div>
                                </div>
                                </div>
HTML;
    }

                                



                                            
    $html2 .= <<<HTML
                                            <div class="col-md-11 mx-auto">
                                            <p class="skill-name" style="margin-top: 50px;">Отключить подачу</p>
                                            </div>
                                            <div class="col-md-11 mx-auto">
                                                    <div class="form-group text-right" style="padding-top: 20px">
                                                    
                                            <label class="switch s-outline s-outline-success  mb-4 mr-2" >
                                                <input type="checkbox"  name="disablefeed" {$disablefeed}>
                                                <span class="slider round"></span>
                                            </label>
                                            
                                            </div>
                                            </div>
                                            

                            </div>
                        </div>
                        </div>
                        
                        
                        
                              
                              <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-three">

                            <div class="widget-heading">
                                <h5 class="">Управление</h5>
                            </div>

                            
{$vlsel}
                            

                                                            <div class="form-group">
                                                                <label for="address">Адресс</label>
                                                                <input type="text" class="form-control mb-4" id="address" name="opaddr" placeholder="Адресс" value="{$prib['addr']}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="address">Название изделия</label>
                                                                <input type="text" class="form-control mb-4" id="name" name="oplcname" placeholder="Название изделия" value="{$prib['loc_name']}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="email">Email*</label>
                                                                <input type="text" class="form-control mb-4" id="email" name="opemail" placeholder="Write your email here" value="{$prib['op_email']}">
                                                            </div>
                            </div>
                        </div>
                        
HTML;

      $listvideos = array('1'=>'https://www.youtube.com/embed/YE7VzlLtp-4','2'=>'https://www.youtube.com/embed/4IqdquoHLmk','3'=>'https://www.youtube.com/embed/YE7VzlLtp-4','4'=>'https://www.youtube.com/embed/4IqdquoHLmk');


      foreach ($listvideos as $kyevd => $valvd ) {

          $videols .= <<<HTML
              <li class="list-group-item list-group-item-action">
        <div class="n-chk">
            <label class="new-control new-checkbox checkbox-primary w-100 justify-content-between">
              
              <span class="" id="video-link{$kyevd}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg></span>
                <span class="ml-2">
                    List groups are a flexible and powerful component for displaying simple.
                </span>
                <span class="ml-3 d-block">
                    <a href="/?action=edit_pribor&idpr={$idpr}&delvideo={$kyevd}"><span class="badge badge-secondary">Удалить</span></a>
                </span>
            </label>
        </div>
    </li>
HTML;




          $video_js .= <<<HTML
        $('#video-link{$kyevd}').click(function () {
            var src = '{$valvd}';
            $('#videoMedia1').modal('show');
            $('<iframe>').attr({
                'src': src,
                'width': '560',
                'height': '315',
                'allow': 'encrypted-media'
            }).css('border', '0').appendTo('#videoMedia1 .video-container');
        }); 

HTML;


    }

        $html2 .= <<<HTML
                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing modal-video">
                        <div class="widget widget-table-three">

                            <div class="widget-heading">
                                <h5 class="">Видео</h5>
                            </div>

                            <div class="widget-content">
                            
                            
                            
                            <ul class="list-group task-list-group">

                            {$videols}
</ul>
      <p></p>                      
                          
                              
                                <div class="custom-file mb-4">
    <input type="file" class="custom-file-input" id="customFile">
    <label class="custom-file-label" for="customFile">Choose file</label>
</div>
                                
                            </div>
                        </div>
                 <!-- Modal -->
<div class="modal fade" id="videoMedia1" tabindex="-1" role="dialog" aria-labelledby="videoMedia1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" id="videoMedia1Label">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="video-container">
                </div>
            </div>
        </div>
    </div>
</div>
       
                        </div>
HTML;

    $html_js .= <<<HTML
           <script>
    $(document).ready(function (d) {
              {$video_js}
           });
     </script>
HTML;





    if($user_role!='1') {
        $html2 .= <<<HTML
     
                          
                       
                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-three">

                            <div class="widget-heading">
                                <h5 class="">Права доступа</h5>
                            </div>

                            <div class="widget-content">


HTML;

            $html2 .= <<<HTML
<div class="n-chk">
    <label class="new-control new-checkbox new-checkbox-rounded checkbox-success">
      <input type="checkbox" class="new-control-input" name="allow_addvideo" {$allow_addvideo2}>
      <span class="new-control-indicator"></span>Ролики
    </label>
</div>
HTML;

        $html2 .= <<<HTML
<div class="n-chk">
    <label class="new-control new-checkbox new-checkbox-rounded checkbox-success">
      <input type="checkbox" class="new-control-input" name="allow_powerdz" {$allow_powerdz2}>
      <span class="new-control-indicator"></span>Сила дезинф.
    </label>
</div>
HTML;

        $html2 .= <<<HTML
<div class="n-chk">
    <label class="new-control new-checkbox new-checkbox-rounded checkbox-success" >
      <input type="checkbox" class="new-control-input" name="allow_supply" {$allow_supply2}>
      <span class="new-control-indicator"></span>Время подача жид-ти
    </label>
</div>
HTML;
        
//        $html2 .= <<<HTML
//<div class="n-chk">
//    <label class="new-control new-checkbox new-checkbox-rounded checkbox-success">
//      <input type="checkbox" class="new-control-input" name="allow_durationdz" {$allow_durationdz2}>
//      <span class="new-control-indicator"></span>Длитель. струи
//    </label>
//</div>
//HTML;

        $html2 .= <<<HTML
<div class="n-chk">
    <label class="new-control new-checkbox new-checkbox-rounded checkbox-success">
      <input type="checkbox" class="new-control-input" name="allow_volume" {$allow_volume2}>
      <span class="new-control-indicator"></span>Громкость
    </label>
</div> 
HTML;

        $html2 .= <<<HTML
<div class="n-chk">
    <label class="new-control new-checkbox new-checkbox-rounded checkbox-success">
      <input type="checkbox" class="new-control-input" name="allow_temperature" {$allow_temperature2}>
      <span class="new-control-indicator"></span>Температура
    </label>
</div>
HTML;



        $html2 .= <<<HTML
                            </div>
                        </div>
                        </div> 
                        
HTML;

    }


    $sql_zamer = $db->query("SELECT * FROM everyday WHERE prid='{$prib['id']}'" );

    while ( $row22 = $db->get_row( $sql_zamer ) ) {
               $conzam++;
        $tablzamer .= <<<HTML
                                        <tr>
                                        <td>{$conzam}</td>
                                        <td>{$row22['allmess']}</td>
                                        <td>{$row22['redmess']}</td>
                                        <td>{$row22['alldoze']}</td>
                                        <td>{$row22['dt']}</td>
                                        </tr>
HTML;

    }



    $html2 .= <<<HTML
                                              <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                   
                                   <div class="section contact">
                                     <div class="info">
                                            
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                   
                                                   <button id="add-education" class="btn btn-primary">Сохранить</button>
                                                </div>    
                                                
                                         </div>
                                      </div>
                                    </div>
                     </div>
                         
                         
                     <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                   
                                   <div class="section contact">
                                     <div class="info">
                                            <h5 class="">Журнал замеров</h5>
                                        <div class="row">
                                   <div class="table-responsive mb-4">
                                
                                    <table id="all-company" class="table style-3  table-hover">
                                        <thead>
                                            <tr>                                              
                                                <th>#</th>
                                                <th>Всего замеров</th>
                                                <th>Ср. тем-ра</th>
                                                <th>Всего доз</th>
                                                <th>Дата</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {$tablzamer}
                                        </tbody>
                                    </table>
                                    
                                </div>        
                                                
                                         </div>
                                      </div>
                                    </div>
                     </div>
                     
                     
                     
                                     </div>
                                     
                              </div>
                              
                              
                       </div>      
                              
                              
                              
                              
                            </div>
                                        
                                        
                        </div>
                    </div>
                    <input name="activsv" type="hidden" value="1">
                    </form>   
</div>
                                        
                                        

HTML;


    $html_js .= <<<HTML
<script>
/*-----Locking sliders together-----*/

// setting up button clicks

// Store the locked state and slider values.

var lockedState = false,
    lockedSlider = false,
    lockedValues = [60, 80],
    slider1 = document.getElementById('slider1'),
    slider2 = document.getElementById('slider2'),
    //lockButton = document.getElementById('lockbutton'),
    slider1Value = document.getElementById('slider1-span'),
    slider2Value = document.getElementById('slider2-span'),
    slider3Value = document.getElementById('slider3-span'),
    slider4Value = document.getElementById('slider4-span');
// When the button is clicked, the locked
// state is inverted.

// lockButton.addEventListener('click', function(){
//     lockedState = !lockedState;
//     this.textContent = lockedState ? 'unlock' : 'lock';
// });


// cross updating

function crossUpdate ( value, slider ) {

    // If the sliders aren't interlocked, don't
    // cross-update.
    //if ( !lockedState ) return;

    // Select whether to increase or decrease
    // the other slider value.
    var a = slider1 === slider ? 0 : 1, b = a ? 0 : 1;

    // Offset the slider value.
    value -= lockedValues[b] - lockedValues[a];

    // Set the value
    slider.noUiSlider.set(value);
}
HTML;
// initializing silders
    if($users_access['allow_powerdz']=='1') {
        $html_js .= <<<HTML
noUiSlider.create(slider1, {
    start: {$prib['op_powerdisin']},
    // Disable animation on value-setting,
    // so the sliders respond immediately.
    animate: false,
    tooltips: true,
    range: {
        min: 0,
        max: 100
    }
});
slider1.noUiSlider.on('update', function( values, handle ){
    slider1Value.value = values[handle];
});
HTML;
    }
    if($users_access['allow_supply']=='1') {
        $html_js .= <<<HTML
noUiSlider.create(slider2, {
    start: {$prib['op_supplytime']},
    animate: false,
    tooltips: true,
    range: {
        min: 0,
        max: 100
    }
});
slider2.noUiSlider.on('update', function( values, handle ){
    slider2Value.value = values[handle];
});
HTML;
    }
    if($users_access['allow_volume']=='1') {
        $html_js .= <<<HTML
noUiSlider.create(slider3, {
    start: {$prib['op_volume']},
    animate: false,
    tooltips: true,
    range: {
        min: 0,
        max: 100
    }
});
slider3.noUiSlider.on('update', function( values, handle ){
    slider3Value.value = values[handle];
});
HTML;
    }
    if($users_access['allow_temperature']=='1') {
        $html_js .= <<<HTML
noUiSlider.create(slider4, {
    start: {$prib['op_templimit']},
    animate: false,
    tooltips: true,
    range: {
        min: 0,
        max: 100
    }
});
slider4.noUiSlider.on('update', function( values, handle ){
    slider4Value.value = values[handle];
});
HTML;
    }
    $html_js .= <<<HTML

</script>
HTML;

}}
}