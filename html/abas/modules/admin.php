<?php
if( !defined( 'ACCESSDOC' ) ) {
    header( "HTTP/1.1 403 Forbidden" );
    die( "Hacking attempt!" );
}

if(!$is_loged_in) {
    header("Location: /"); exit();
}

$html_css = <<<HTML
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
<link href="assets/css/pages/error/style-400.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="plugins/dropify/dropify.min.css">
    <link href="assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />


    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="plugins/noUiSlider/nouislider.min.css" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    
    <link href="plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="plugins/noUiSlider/custom-nouiSlider.css" rel="stylesheet" type="text/css">
    <link href="plugins/bootstrap-range-Slider/bootstrap-slider.css" rel="stylesheet" type="text/css">
    <link href="assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link href="assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css">
    <!--  END CUSTOM STYLE FILE  -->
HTML;
$html_js = <<<HTML
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <script src="plugins/apex/apexcharts.min.js"></script>
    <script src="assets/js/dashboard/dash_2.js"></script>
    <script src="plugins/highlight/highlight.pack.js"></script>
    <script src="assets/js/scrollspyNav.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="plugins/table/datatable/datatables.js"></script>

    <!-- END PAGE LEVEL SCRIPTS -->  
    <script src="plugins/dropify/dropify.min.js"></script>
    <script src="plugins/blockui/jquery.blockUI.min.js"></script>
    <!-- <script src="plugins/tagInput/tags-input.js"></script> -->
    <script src="assets/js/users/account-settings.js"></script>

    <script src="plugins/flatpickr/flatpickr.js"></script>
    <script src="plugins/noUiSlider/nouislider.min.js"></script>


    <script src="plugins/bootstrap-range-Slider/bootstrap-rangeSlider.js"></script>
HTML;

$html_js .= <<<HTML
    <script>

        c1 = $('#list-admin').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 20
        });

        multiCheck(c1);
        
          c2 = $('#list-pribor').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 20,
	        columnDefs: [ {
	            targets: [ 9 ],
	            orderData: [ 9 ]
	        }, {
	            targets: [ 10 ],
	            orderData: [ 10 ]
	        }]
        });

        multiCheck(c2);
        
        
         c3 = $('#all-company').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 20
        });

        multiCheck(c3);
        
                 c4 = $('#all-list-pribor').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 20
        });

        multiCheck(c4);
    </script>
HTML;






if (!$action) {
    include_once(ROOT_DIR . '/modules/list_pribor.php');

}elseif($action=='add_user' and ($mod == 'company' or $mod == 'admin' or $mod == 'operator')){
    include_once(ROOT_DIR . '/modules/add_user.php');
}elseif($action=='list_company'){
    include_once(ROOT_DIR . '/modules/list_company.php');
}elseif($action=='list_admin'){
    include_once(ROOT_DIR . '/modules/list_admin.php');
}elseif($action=='list_operator'){
    include_once(ROOT_DIR . '/modules/list_operator.php');
}elseif($action=='edit_pribor' and $idpr>0){
    include_once(ROOT_DIR . '/modules/edit_pribor.php');
}elseif($action=='edit_user'){
    include_once(ROOT_DIR . '/modules/edit_user.php');
}else{


    $class_body ="error404 text-center";
    $html2 .= <<<HTML
<div class="container-fluid error-content">
        <div class="">
            <h1 class="error-number">404</h1>
            <p class="mini-text">Ooops!</p>
            <p class="error-text mb-4 mt-1">The page you requested was not found!</p>
            <a href="/" class="btn btn-primary mt-5">Go Back</a>
        </div>
    </div>
HTML;
}

if($html2==''){
    $class_body ="error404 text-center";
    $html2 .= <<<HTML
<div class="container-fluid error-content">
        <div class="">
            <h1 class="error-number">404</h1>
            <p class="mini-text">Ooops!</p>
            <p class="error-text mb-4 mt-1">The page you requested was not found!</p>
            <a href="/" class="btn btn-primary mt-5">Go Back</a>
        </div>
    </div>
HTML;

}



include_once(ROOT_DIR . '/templates/header.php');


$class_body ="";

$html ="";


//актив меню
if (!$action or $action=='edit_pribor') {//0
    $actmenu = array(0 => 'true',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='add_user' and $mod=='company'){//1
    $actmenu = array(0 => 'false',1 => 'true',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='list_company'){//2
    $actmenu = array(0 => 'false',1 => 'false',2 => 'true',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='list_admin'){//3
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'true',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='add_user' and $mod=='admin'){//4
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'true',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='add_user' and $mod=='operator'){//5
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'true',6 => 'true',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='list_operator'){//6
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'true',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='7'){//7
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'true',8 => 'false',9 => 'false',10 => 'false');
}elseif ($action=='8'){//8
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'true',9 => 'false',10 => 'true');
}elseif ($action=='9'){//9
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'true',10 => 'false');
}elseif ($action=='10'){//10
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'false',10 => 'true');
}else{
    $actmenu = array(0 => 'false',1 => 'false',2 => 'false',3 => 'false',4 => 'false',5 => 'false',6 => 'false',7 => 'false',8 => 'false',9 => 'false',10 => 'false');
}


$html .= <<<HTML
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm expand-header">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

            <ul class="navbar-item flex-row ml-auto">


                
               

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                    </a>
                    <div class="dropdown-menu position-absolute e-animated e-fadeInUp" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">                            
                            <div class="media mx-auto">
                               
                                <div class="media-body">
                                    <h5>{$_SESSION['abas_login']} ({$lvluser})</h5>
                                 
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="/?action=edit_user">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>{$lang['my_profile']}</span>
                            </a>
                        </div>

                        <div class="dropdown-item">
                            <a href="/?action=logout">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>{$lang['log_out']}</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container sidebar-closed sbar-open" id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">
            
            <nav id="sidebar">

                <ul class="navbar-nav theme-brand flex-row  text-center">
                    <!--<li class="nav-item theme-logo">-->
                        <!--<a href="/">-->
                            <!--<img src="assets/img/90x90.jpg" class="navbar-logo" alt="logo">-->
                        <!--</a>-->
                    <!--</li>-->
                    <li class="nav-item theme-text">
                        <a href="/" class="nav-link"> {$_SESSION['abas_login']} ({$lvluser})[{$user_role}]</a>
                    </li>
                </ul>

                <ul class="list-unstyled menu-categories" id="accordionExample">
                    

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span>Меню</span></div>
                    </li>

                    <li class="menu">
                        <a href="/" aria-expanded="{$actmenu[0]}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span>Все приборы</span>
                            </div>
                        </a>
                    </li>
HTML;
if($user_role == '4') { //доступ для овнера
    $html .= <<<HTML
                    <li class="menu">
                        <a href="/?action=add_user&mod=company" aria-expanded="{$actmenu[1]}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span>Добавить Предприятие</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu">
                        <a href="/?action=list_company" aria-expanded="{$actmenu[2]}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span>Все Предприятия</span>
                            </div>
                        </a>
                    </li>
                   
HTML;
}
if($user_role == '3') { //доступ для компании
    $html .= <<<HTML
                   
                     <li class="menu">
                        <a href="/?action=list_admin" aria-expanded="{$actmenu[3]}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span>Все Администраторы</span>
                            </div>
                        </a>
                    </li>
                     <li class="menu">
                        <a href="/?action=add_user&mod=admin" aria-expanded="{$actmenu[4]}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span>Добавить Администратора</span>
                            </div>
                        </a>
                    </li>
HTML;
}
if($user_role == '2') { //доступ для админа
    $html .= <<<HTML
                   
                    <li class="menu">
                        <a href="/?action=add_user&mod=operator" aria-expanded="{$actmenu[5]}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span>Добавить Оператора</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="/?action=list_operator" aria-expanded="{$actmenu[6]}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span>Все Операторы</span>
                            </div>
                        </a>
                    </li>
HTML;
}
if($user_role == '1') { //доступ для оператора
    $html .= <<<HTML

HTML;
}
$html .= <<<HTML




                    <li class="menu">
                        <div  aria-expanded="true" class="dropdown-toggle">
                            
                        </div>
                    </li>
                    




                   
                    
                   
                    
                </ul>
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->
        
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                

                    

HTML;


//вывод контента

$html .= ''.$html2.'';




$html .= <<<HTML


                

            </div>
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->
HTML;



echo $html;

include_once(ROOT_DIR . '/templates/footer.php');


?>