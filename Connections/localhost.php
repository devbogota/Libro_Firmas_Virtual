<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
//error_reporting(0);

////PRODUCCION

$hostname_conecta = "92.42.111.41";
$database_conecta = "losolivo_portal";
$username_conecta = "losolivo_usrport";
$password_conecta = "+35M7gIcUd9oAV*DFi";

/////PRUEBAS

/*$hostname_conecta = "localhost";
$database_conecta = "losolivo_micro";
$username_conecta = "micro";
$password_conecta = "Micro.3143*.10";
$pagina_inicio='http://localhost/prevision/index.php';*/

/*
/////PRUEBAS HOME
$hostname_conecta = "localhost";
$database_conecta = "losolivo_madres2020";
$username_conecta = "root";
$password_conecta = "";
*/
//$conecta = new mysqli($hostname_conecta, $username_conecta, $password_conecta) or trigger_error(mysql_error(),E_USER_ERROR);

$conecta = mysqli_connect($hostname_conecta, $username_conecta, $password_conecta) or trigger_error(mysqli_error($conecta),E_USER_ERROR);

//mysql_pconnect
?>