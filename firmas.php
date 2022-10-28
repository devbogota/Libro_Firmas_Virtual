<?php 
require_once('Connections/localhost.php');
require_once('funciones.php');

if (isset($_GET['ID_SQ']))
{
   $ID_SQ = $_GET['ID_SQ'];
}
else
{
	 $ID_SQ='';
}

?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Libro de firmas</title>
<link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="estilos.css" rel="stylesheet" type="text/css">
<!-- 
Para obtener más información sobre los comentarios condicionales situados alrededor de las etiquetas html en la parte superior del archivo:
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/
Haga lo siguiente si usa su compilación personalizada de modernizr (http://www.modernizr.com/):
* inserte el vínculo del código js aquí
* elimine el vínculo situado debajo para html5shiv
* añada la clase "no-js" a las etiquetas html en la parte superior
* también puede eliminar el vínculo con respond.min.js si ha incluido MQ Polyfill en su compilación de modernizr 
-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>    
<script src="respond.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet">
<script>

function validar()
{
	
	var mensaje= document.getElementById('mensaje').value;
	var ID_SQ= document.getElementById('ID_SQ').value;
	var nombre= document.getElementById('nombre').value;
	var correo= document.getElementById('buyerEmail').value;
	var celular= document.getElementById('celular').value;
	
	if(mensaje == null || mensaje.length == 0 || /^\s+$/.test(mensaje))
	{
		alert('Por favor ingresa el mensaje');
		document.getElementById('mensaje').focus();
		return false;
	}
	if(nombre == null || nombre.length == 0 || /^\s+$/.test(nombre))
	{
		alert('Por favor ingresa tu nombre y apellido');
		document.getElementById('nombre').focus();
		return false;
	}
	if(!(/\S+@\S+\.\S+/.test(correo))){
      alert('Por favor ingresa tu correo electrónico');
       document.getElementById('buyerEmail').focus();
	  return false;
    }
	if(celular == null || celular.length <= 9 || isNaN(celular) || celular.length > 10 )
	 {
      alert("Por favor ingresa tu número celular");
		 document.getElementById('celular').focus();
	  return false;
    }
	
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (xhttp.readyState == 4 && xhttp.status == 200) {
	document.getElementById("formulario_beneficiarios").innerHTML = xhttp.responseText;
	}
	};
	xhttp.open("POST", "inserta_mensaje.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send(
	"&mensaje="+mensaje+
	"&nombre="+nombre+
	"&correo="+correo+
	"&celular="+celular+
	"&ID_SQ="+ID_SQ);
	alert("El mensaje se envío correctamente");
	document.form1.submit();
	
}
</script>
</head>
<body class="firmas">
<div >
  <div id="">
     <table width="100%">
      <tr>
        <td><?php datos_fallecido1($ID_SQ);?></td>
      </tr>
      <tr>
        <td><?php firmas($ID_SQ);?></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>