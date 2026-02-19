<!-- <?php
require_once('Connections/localhost.php');
require_once('funciones.php');

if (isset($_GET['ID_SQ'])) {
	$ID_SQ = $_GET['ID_SQ'];
} else {
	$ID_SQ = '';
}

?>
<!doctype html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Libro de firmas</title>
	<link href="boilerplate.css" rel="stylesheet" type="text/css">
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script>
		function validar() {

			var nombre = document.getElementById('nombre').value;
			var mensaje = document.getElementById('mesage').value;
			var correo = document.getElementById('email').value;
			var celular = document.getElementById('cell').value;
			var acepta = document.getElementById('acepta').checked;

			console.log(nombre)

			if (nombre == null || nombre.length == 0 || /^\s+$/.test(nombre)) {
				alert('Por favor ingresa tu nombre y apellido');
				document.getElementById('nombre').focus();
				return false;
			}
			if (!(/\S+@\S+\.\S+/.test(correo))) {
				alert('Por favor ingresa tu correo electrónico');
				document.getElementById('email').focus();
				return false;
			}
			if (celular == null || celular.length <= 9 || isNaN(celular) || celular.length > 10) {
				alert("Por favor ingresa tu número celular");
				document.getElementById('celular').focus();
				return false;
			}
			if (mensaje == null || mensaje.length == 0 || /^\s+$/.test(mensaje)) {
				alert('Por favor ingresa el mensaje');
				document.getElementById('mensaje').focus();
				return false;
			}
			if (acepta) {
				//alert('checkbox esta seleccionado');
			} else {
				alert('Para continuar debes aceptar los términos y condiciones');
				document.getElementById('acepta').focus();
				return false;
			}

			alert('tu mensaje se guardo correctamente')

			// var xhttp = new XMLHttpRequest();
			// xhttp.onreadystatechange = function() {
			// 	if (xhttp.readyState == 4 && xhttp.status == 200) {
			// 		document.getElementById("formulario_beneficiarios").innerHTML = xhttp.responseText;
			// 	}
			// };
			// xhttp.open("POST", "inserta_mensaje.php", true);
			// xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			// xhttp.send(
			// 	"&mensaje=" + mensaje +
			// 	"&nombre=" + nombre +
			// 	"&correo=" + correo +
			// 	"&celular=" + celular +
			// 	"&ID_SQ=" + ID_SQ);
			// alert("El mensaje se envío correctamente");
			// document.form1.submit();
		}
	</script>
	<style>
		
	</style>
</head>

<body class="firmas">
	<div id="myModal" class="modal fade">
		<div class="modal-dialog modal-login">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title mx-auto">Deja un mensaje para tu ser querido</h4>
				</div>
				<div class="modal-body">
					<form action="/examples/actions/confirmation.php" method="post">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="text" class="form-control" id="email" name="mail" placeholder="Correo" required="required">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="text" class="form-control" id="cell" name="cell" placeholder="Celular" required="required">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="text" class="form-control" id="mesage" name="mesage" placeholder="Mensaje" required="required">
							</div>
						</div>
						<tr>
							<td class="titulos_cajas mb-5">
								<table width="100%" class="mb-5" border="0">
									<tr>
										<td width="15%" align="right"><input name="acepta" type="checkbox" class="check_va" id="acepta" value="1" />
											<label for="acepta"></label>
										</td>
										<td width="85%" align="center"> He leído y Acepto la
											<a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Política de Privacidad</a>
										</td>
									</tr>
									<div class="accordion mb-5" id="accordionExample">
										<div class="card">
											<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
												<div class="card-body text-center text-center">
													<h2>Política de Privacidad</h2>
													<p>La CENTRAL COOPERATIVA DE SERVICIOS FUNERARIOS – COOPSERFUN, dando cumplimiento a lo dispuesto en la Ley 1581 de 2012, "Por el cual se
														dictan disposiciones generales para la protección de datos personales" y sus decretos reglamentarios, y de acuerdo a la política de tratamiento de datos
														personales publicada en la página www. losolivosbogota.co y www.funerarialacandelaria.co, manifiesta que los datos personales que se registren en
														el libro de firmas de las salas de velación, previa autorización del titular de la información, serán recolectados y tratados conforme a la política de
														tratamiento de datos de COOPSERFUN.
														Así mismo con la firma del presente libro manifiestan expresamente que autorizan, de manera libre, previa y voluntaria a la CENTRAL COOPERATIVA
														DE SERVICIOS FUNERARIOS – COOPSERFUN - para que realice la recolección, almacenamiento, uso, circulación, supresión de información,
														ofrecimientos de servicios funerarios, envíos de actos de publicidad, información comercial, llamadas comerciales, información sobre eventos,
														evaluaciones de la calidad del servicio y tratamiento de los datos personales. Por consiguiente, declaran haber sido informados sobre el tratamiento
														que recibirán los datos personales registrados, así como sobre los derechos que me asisten como titular de los mismos y sobre la dirección física y/o
														electrónica del responsable del tratamiento de dicha información.</p>
													</br>
													</br>
													<div class="cierramodal2">
														<a href="#" class="cerrarmodal2" class="trigger-btn" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Cerrar</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</table>
							</td>
						</tr>
						<div class="form-group">
							<button onclick="validar()" data-dismiss="modal" class="btn btn-primary btn-block btn-lg">Enviar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<nav>

		<div style="width: 100%;">
			<img src="./imagenes/BANNER2911.png" alt="">
		</div>

	</nav>
	<div>
		<div id="">
			<table width="100%">
				<tr>
					<td><?php datos_fallecido1($ID_SQ); ?></td>
				</tr>
				<tr>
					<td><?php firmas($ID_SQ); ?></td>
				</tr>
			</table>
		</div>
	</div>
	<footer>
		<div style="width: 100%;">
			<img src="./imagenes/FOOTER2911.png" alt="">
		</div>
	</footer>
</body>
<script>
	$(document).ready(function() {

		$('#myModal').modal({
			backdrop: 'static'
		})
		$('#myModal').modal('toggle')
	});
</script>

</html> -->
