<?PHP


if( !defined( 'ACCESSDOC' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	die( "Hacking attempt!" );
}

$html = <<<HTML
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    {$html_js}
    

</body>
</html>
HTML;
echo $html;
?>