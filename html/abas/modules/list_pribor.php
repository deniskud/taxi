<?php
if( !defined( 'ACCESSDOC' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    die( "Hacking attempt!" );
}




?>

<?php




$html2 .= <<<HTML
<div class="account-settings-container layout-top-spacing">

                   <div class="account-content">
                        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                            <div class="row">
                            
                            
                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <div class="statbox widget box box-shadow">

                            
                            
                             <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>Все приборы</h4>
                                    </div>
                                </div>
                            </div>
                             {$butnewpr}
                            <div class="widget-content widget-content-area">
                            
                                <div class="table-responsive">
                                    <table class="table style-3  table-hover" id="all-list-pribor">
                                        <thead>
                                            <tr>
                                                <th><div class="th-content">No</div></th>
                                                <th><div class="th-content">Предприятие</div></th>
                                                <th><div class="th-content">Админ</div></th>
                                                <th><div class="th-content">Оператор</div></th>
                                                <th><div class="th-content">Название</div></th>
                                                <th><div class="th-content th-heading">S.N.</div></th>
                                                <th><div class="th-content">Адресс</div></th>
                                                <th><div class="th-content">Уровень жидкости</div></th>
                                                <th><div class="th-content">Кол-во дезинфекций</div></th>
                                                <th><div class="th-content">Кол-во замеров</div></th>
                                                <th><div class="th-content">Средняя тем-ра</div></th>
                                                <th><div class="th-content">дата замены жидкости</div></th>
                                                <th><div class="th-content">Последнее открытие</div></th>
                                                <th><div class="th-content">Дверка</div></th>
                                                <th><div class="th-content">Статус</div></th>
                                                <th><div class="th-content"></div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
HTML;

//все приборы
if($user_role == '4'){$seluser = "owid = '{$user_id}'";}
if($user_role == '3'){$seluser = "cmpid = '{$user_id}'";}
if($user_role == '2'){$seluser = "admid = '{$user_id}'";}
if($user_role == '1'){$seluser = "prib.operid = '{$user_id}'";}


//приборы для сабпользователя
if($user_role == '4' and $user['user_id']){$seluser = "cmpid = '{$user['user_id']}'";}
if($user_role == '3' and $user['user_id']){$seluser = "admid = '{$user['user_id']}'";}
if($user_role == '2' and $user['user_id']){$seluser = "prib.operid = '{$user['user_id']}'";}




//$sql_result = $db->query("SELECT prib.id, prib.serial, prib.addr FROM pribors as prib LEFT JOIN (SELECT SUM(allmess) as sum_allmess FROM everyday) eve ON eve.prid=prib.id" );

$sql_sq = "SELECT prib.*, 
SUM(eve.allmess) as sum_allmess, SUM(eve.alldoze) as sum_alldoze, AVG(eve.redmess) as sr_redmess,
prib.id as priborid, 
op.name as name_oper, 
zamena_tmp.start as zmstart, 
bobber_tmp.changestatu as bobber_stat, 
doorstatus_tmp.changestatu as doorstatus_stat, doorstatus_tmp.dt as door_dt ,
onlinestatus_tmp.changestatu as onlinestatus_stat,
com.name as com_name,
adm.name as adm_name

FROM pribors as prib 
LEFT JOIN everyday eve ON (eve.prid=prib.id) 
LEFT JOIN opers op ON (op.user_id=prib.operid)
LEFT JOIN companies com ON (com.user_id=prib.cmpid)
LEFT JOIN admins adm ON (adm.user_id=prib.admid)

LEFT JOIN (SELECT bo1.* 
FROM bobber as bo1 
LEFT JOIN bobber as bo2
		     ON bo1.prid = bo2.prid AND bo1.dt < bo2.dt WHERE bo2.prid IS NULL) as bobber_tmp ON (prib.id = bobber_tmp.prid)
		     
LEFT JOIN (SELECT re1.* 
FROM recharges as re1 
LEFT JOIN recharges as re2
		     ON re1.prid = re2.prid AND re1.start < re2.start WHERE re2.prid IS NULL) as zamena_tmp ON (prib.id = zamena_tmp.prid)
		     
LEFT JOIN (SELECT do1.* 
FROM doorstatus as do1 
LEFT JOIN doorstatus as do2
		     ON do1.prid = do2.prid AND do1.dt < do2.dt WHERE do2.prid IS NULL) as doorstatus_tmp ON (prib.id = doorstatus_tmp.prid)
		     
LEFT JOIN (SELECT on1.* 
FROM onlinestatus as on1 
LEFT JOIN onlinestatus as on2
		     ON on1.prid = on2.prid AND on1.dt < on2.dt WHERE on2.prid IS NULL) as onlinestatus_tmp ON (prib.id = onlinestatus_tmp.prid)
WHERE {$seluser}		     
GROUP BY prib.id";



$sql_result = $db->query($sql_sq);

while ( $row = $db->get_row( $sql_result ) ) {
    $conapr++;

    if(!$row['sum_alldoze']){$row['sum_alldoze'] = "-/-";}
    if(!$row['sum_allmess']){$row['sum_allmess'] = "-/-";}
    if(!$row['sr_redmess']){$row['sr_redmess'] = "-/-";}else{$row['sr_redmess'] = round($row['sr_redmess'], 1)."℃";}
    if(!$row['bobber_stat']){$row['bobber_stat'] = "-/-";}

    if($row['doorstatus_stat']=='0'){
        $row['doorstatus_stat'] = "outline-badge-danger";
        $doorstatus_stat_text = 'Закрыта';  //Закрыта
    }elseif($row['doorstatus_stat']=='1'){
        $row['doorstatus_stat'] = "outline-badge-success";
        $doorstatus_stat_text = 'Открыта'; //Открыта
    }else{
        $row['doorstatus_stat'] = "outline-badge-primary";
        $doorstatus_stat_text = 'Не данных';// Не данных
    }

    if($row['onlinestatus_stat']=='0'){
        $row['onlinestatus_stat'] = "outline-badge-danger";
        $onlinestatus_stat_text = 'Offline';  // Offline
    }elseif($row['onlinestatus_stat']=='1'){
        $row['onlinestatus_stat'] = "outline-badge-success";
        $onlinestatus_stat_text = 'Online';  //Online
    }else{
        $row['onlinestatus_stat'] = "outline-badge-primary";
        $onlinestatus_stat_text = 'Не данных';  //Не данных
    }



    //$zamena = date( "Y-m-d", $row['zmstart'] );
if(!$row['zmstart']){
    $zamena = '-/-';
}else{
    $date_z = new DateTime($row['zmstart']);
    $zamena = $date_z->format('d-m-Y H:i');
}
    if(!$row['door_dt']){
        $door_dt = '-/-';
    }else{
        $date_z = new DateTime($row['door_dt']);
        $door_dt = $date_z->format('d-m-Y H:i');
    }

    if($row['com_name']=='') {
        $com_name = '--/--';
    }else{
        $com_name = $row['com_name'];
    }
    if($row['adm_name']=='') {
        $adm_name = '--/--';
    }else{
        $adm_name = $row['adm_name'];
    }

    $html2 .= <<<HTML
                                            <tr>
                                                <td><div class="td-content pricing"><span class="">{$conapr}</span></div></td>
                                                <td><div class="td-content product-brand">{$com_name}</div></td>
                                                <td><div class="td-content product-brand">{$adm_name}</div></td>
                                                <td><div class="td-content product-brand">{$row['name_oper']}</div></td>
                                                <td><div class="td-content product-brand">{$row['loc_name']}</div></td>
                                               <td><div class="td-content pricing"><span class="">{$row['serial']}</span></div></td>
                                                <td><div class="td-content product-brand">{$row['addr']}</div></td>
                                                <td><div class="td-content pricing"><span class="">{$row['bobber_stat']} %</span></div></td>
                                                <td><div class="td-content pricing"><span class="">{$row['sum_alldoze']}</span></div></td>
                                                <td><div class="td-content pricing"><span class="">{$row['sum_allmess']}</span></div></td>
                                                <td><div class="td-content pricing"><span class="">{$row['sr_redmess']}</span></div></td>
                                                <td data-order="{$row['zmstart']}"><div class="td-content">{$zamena}</div></td>
                                                <td data-order="{$row['door_dt']}"><div class="td-content">{$door_dt}</div></td>
                                                <td><div class="td-content"><span class="badge {$row['doorstatus_stat']}">{$doorstatus_stat_text}</span></div></td>
                                                <td><div class="td-content"><span class="badge {$row['onlinestatus_stat']}">{$onlinestatus_stat_text}</span></div></td>
                                                <td><div class="td-content"><a href="/?action=edit_pribor&idpr={$row['priborid']}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></div></td>
                                            </tr>
HTML;

}



$html2 .= <<<HTML
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
HTML;
