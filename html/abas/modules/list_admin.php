<?php
if( !defined( 'ACCESSDOC' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    die( "Hacking attempt!" );
}




?>

<?php


if($user_role == '3') { //доступ для предприятия


        $titleh5 = $lang['listadin_titl'];//Таблица админов






    if(intval($_GET['del'])>0) {

        $countcomp = $db->super_query("SELECT *, COUNT(id) as count FROM users as us,admins as adm WHERE  us.user_id = '{$_GET['del']}' and us.user_role='2' and us.user_id = adm.user_id and adm.copmp_id='{$user_id}'");



        if ($countcomp['count'] == '1') {
           $db->query("DELETE FROM admins WHERE user_id = '{$_GET['del']}' and copmp_id='{$user_id}'"); //удаляем профиль админа пользователя
            $db->query("DELETE FROM users WHERE user_id = '{$_GET['del']}' and user_role='2'"); //удаляем пользователя


            $sql_result2 = $db->query("SELECT id FROM pribors WHERE admid='{$_GET['del']}'" ); //находим приборы данного админа

            while ( $row2 = $db->get_row( $sql_result2 ) ) {

                $db->query( "UPDATE pribors SET admid='0',operid='0' WHERE id = '{$row2['id']}'" ); //сбрасываем админа и оператора в приборах
            }

            $err = <<<HTML
<div class="col-md-12"> 
<div class="alert alert-success mb-4" role="alert"> 
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
<line x1="18" y1="6" x2="6" y2="18"></line>
<line x1="6" y1="6" x2="18" y2="18"></line>
</svg>
</button><ul><li> <strong>Success!</strong></li> <li> Комания '{$countcomp['name']}' удалена.</li> </ul>
</div></div>
HTML;
        }else{
            $err = <<<HTML
<div class="col-md-12"> 
<div class="alert alert-danger mb-4" role="alert"> 
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
<line x1="18" y1="6" x2="6" y2="18"></line>
<line x1="6" y1="6" x2="18" y2="18"></line>
</svg>
</button><ul> <li><strong>Error!</strong></li> <li> Не удалось удалить!</li> </ul>
</div></div>
HTML;
        }
    }

    $sql_result = $db->query("SELECT adm.*, COUNT(prib.admid) as countprib , user_login 
FROM admins as adm 
LEFT JOIN pribors prib ON (prib.admid=adm.user_id)
LEFT JOIN users us ON (us.user_id = adm.user_id) 
WHERE adm.copmp_id='{$user_id}' 
GROUP BY adm.id" );

    while ( $row = $db->get_row( $sql_result ) ) {
        $conap++;


        $listsubuser='';

        $sql_result3 = $db->query("SELECT id,name FROM opers WHERE adm_id='{$row['user_id']}'" );
        while ( $row3 = $db->get_row( $sql_result3 ) ) {
            $listsubuser .= "<a href='#'>{$row3['name']}</a><br>";
        }


        $htmlres .= <<<HTML
                                            <tr>
                                                <td class="checkbox-column text-center">{$conap}</td>
                                                
                                                <td>{$row['name']}</td>
                                                <td>{$row['user_login']}</td>
                                                <td>{$row['addr']}</td>
                                                <td>{$row['tel1']}</td>
                                             
                                                <td class="adminlink">
                                                {$listsubuser}
                                                </td>
                                                <td>{$row['countprib']}</td>
                                                <td class="text-center">
                                                    <ul class="table-controls">
                                                        <li><a href="/?action=add_user&mod=admin&idu={$row['user_id']}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                        <li><a href="/?action=list_admin&del={$row['user_id']}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a></li>
                                                    </ul>
                                                </td>
                                            </tr>
HTML;

    }

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
                                        <h4>{$titleh5}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right mb-5">
                                                   
                                                    <a href="/?action=add_user&mod=admin" class="btn btn-primary mb-2 mr-2">Создать нового</a>
                                                </div>
                                                
                                                
                                   {$err}             
                                                
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive mb-4">
                                    <table id="all-company" class="table style-3  table-hover">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column text-center">Id </th>
                                               
                                                <th>Имя</th>
                                                <th>Логин</th>
                                                <th>Адрес</th>
                                                <th>Телефон</th>
                                                <th>Операторы</th>
                                                <th>Кол-во приборов</th>
                                                <th class="text-center">Action</th>
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
                

                
                                
              

                                                
                                                
                                                
                                
                            </div>
                        </div>
                    </div>


                </div>
HTML;


    $html2 .= <<<HTML

HTML;
}