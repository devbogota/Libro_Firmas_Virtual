<?php 
function almacena_sesion($user,$token,$ultimoAcceso,$proceso,$estado)
{
	require('Connections/localhost.php');
	date_default_timezone_set('America/Bogota');
	
	if($proceso=='inicio')
	{
		////INSERTAR REGISTRO
		$insertSQL = sprintf("INSERT INTO sesiones
		(usuario,
		creado,
		actualiza,
		token,
		estado) 
		VALUES
		(
		'$user',
		'$ultimoAcceso',
		'$ultimoAcceso',
		'$token',
		'$estado')");
		//echo"el insert es $insertSQL<br>";
		mysqli_select_db($conecta,$database_conecta);
		$Result1 = mysqli_query($conecta,$insertSQL) or die(mysql_error());	
	}
	if($proceso=='actualiza')
	{
		$insertSQL = sprintf("UPDATE  sesiones SET actualiza='$ultimoAcceso', estado='$estado'
		where usuario='$user' and token='$token'");
		//echo"insertSQL $insertSQL<br>";
		mysqli_select_db($conecta,$database_conecta);
		$Result1 = mysqli_query($conecta,$insertSQL) or die(mysql_error());	
	}
}
function valida_sesion_appNW($user,$token,$ultimoAcceso)
{
	$index="index.php";
	if(empty($user))//cambiar el !
	{
		///SESSION VACIA
		session_destroy();
		header("Location: $index");
		exit();
	}
	else
	{
		/////valida que el estado de la sesion sea valida
		require('Connections/localhost.php');
		
		mysqli_select_db($conecta,$database_conecta);
		$query_bs_da = "select * from sesiones
		where usuario='$user' and token='$token' and estado='2'";
		$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
		$row_bs_da = mysqli_fetch_assoc($bs_da);
		$totalRows_bs_da = mysqli_num_rows($bs_da);
		if($totalRows_bs_da>=1)
		{
			////encontro registro de proceso finalizado, se cierra sesion
			session_destroy(); // destruyo la sesión
			header("Location: index.php"); //envío al usuario a la pag. de autenticación
			exit();
			//sino, actualizo la fecha de la sesión
		}
		else
		{
			//sesion activa en BD
			date_default_timezone_set('America/Bogota');
			$fechaGuardada = $ultimoAcceso;
			$ahora = date("Y-n-j H:i:s");
			$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
			//echo"tiempo trnascurrido es $tiempo_transcurrido<br>";
			if($tiempo_transcurrido >= 300)
			{
				//si pasaron 10 minutos o más
				session_destroy(); // destruyo la sesión
				header("Location: index.php"); //envío al usuario a la pag. de autenticación
				exit();
			}
			else
			{
				$_SESSION["ultimoAcceso"] = $ahora;
				almacena_sesion($user,$token,$ultimoAcceso,'actualiza','1');
			}
		}
	}
}

function firmas($ID_SQ)
{
	require('Connections/localhost.php');
	
	
	mysqli_select_db($conecta,$database_conecta);
	$query_bs_da = "select obituarioProfile,nombre,email,telefono,mensaje from mensajesCondolencia 
	where obituarioProfile='$ID_SQ' and status>0";
	$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
	$row_bs_da = mysqli_fetch_assoc($bs_da);
	$totalRows_bs_da = mysqli_num_rows($bs_da);
	if($totalRows_bs_da==0)
	{
		$estado='No hay mensajes';
	}
	else
	{
	 ?>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="00" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="40%" border="0" align="center" cellpadding="5" cellspacing="5">
          <?php do { ?>
          <tr>
            <td class="letra1MB" align="center"><?php echo $row_bs_da['mensaje'];?></td>
            </tr>
          <tr>
            <td class="titulo" align="center"><?php echo $row_bs_da['nombre'];?></td>
            </tr>
          <tr>
            <td><hr></td>
            </tr>
          <?php } while ($row_bs_da = mysqli_fetch_assoc($bs_da)); ?>
          
        </table></td>
        </tr>
    </table></td>
  </tr>
</table>
	 <?php
}	
}
function datos_fallecido1($ID_SQ)
{
	require('Connections/localhost.php');

	mysqli_select_db($conecta,$database_conecta);
	$query_bs_da = "select id,
	nombreFallecido,
	fechaNacimiento, 
	fechaDeceso,
	sede,
	sala,
	lugarExequias,
	fechaExequias,
	destinoFinal,
	exhumaCementerio,
	perfil_fotoPrincipal 
	from obituariosProfiles where id='$ID_SQ'";
	$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysqli_error($conecta));
	$row_bs_da = mysqli_fetch_assoc($bs_da);
	$totalRows_bs_da = mysqli_num_rows($bs_da);	
?>	
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
          <tr>
            <td colspan="2" align="center" valign="middle" class="titulos_cajasMB"><hr></td>
          </tr>
          <tr>
            <td width="60%" align="center" valign="middle" class="titulos_cajasMB"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="3">
              <tr>
				<tr>
                <td  align="center" valign="middle" class="texto_firma"><b>† Q.E.P.D. †</b></td>
				</tr>
                <td  align="center" valign="middle" class="texto_firma"><b><?php echo utf8_encode($row_bs_da['nombreFallecido']); ?></b></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="middle" class="titulos_cajasMB"><hr></td>
          </tr>
        </table>
<?php 	

}
function mensajes($ID_SQ)
{
	require('Connections/localhost.php');
	
	
	mysqli_select_db($conecta,$database_conecta);
	$query_bs_da = "select obituarioProfile,nombre,email,telefono,mensaje from mensajesCondolencia 
	where obituarioProfile='$ID_SQ' and status>0";
	$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
	$row_bs_da = mysqli_fetch_assoc($bs_da);
	$totalRows_bs_da = mysqli_num_rows($bs_da);
	if($totalRows_bs_da==0)
	{
		$estado='No hay mensajes';
	}
	else
	{
	 ?>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="00" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="99%" border="0" align="center" cellpadding="5" cellspacing="5">
          <tr>
            <td align="center" valign="middle" bgcolor="#006699">MENSAJES DE CONDOLENCIA</td>
            </tr>
          <?php do { ?>
          <tr>
            <td class="letra1MB"><?php echo $row_bs_da['mensaje'];?></td>
            </tr>
          <tr>
            <td class="titulo"><?php echo $row_bs_da['nombre'];?></td>
            </tr>
          <tr>
            <td><hr></td>
            </tr>
          <?php } while ($row_bs_da = mysqli_fetch_assoc($bs_da)); ?>
          
        </table></td>
        </tr>
    </table></td>
  </tr>
</table>
	 <?php
}	
}
function registra($cedula,$nombre,$apellido,$correo,$celular)
{
		$ID_SQ='';
	
		mysqli_select_db($conecta,$database_conecta);
		$query_bs_da = "select id_registro from evento 
		where cedula='$cedula'";
		$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
		$row_bs_da = mysqli_fetch_assoc($bs_da);
		$totalRows_bs_da = mysqli_num_rows($bs_da);
		mysqli_free_result($bs_da);
	if($totalRows_bs_da==0)
	{
	
		date_default_timezone_set('America/Bogota');
		$fecha=date('Y-m-d');
		$hora=date('H-i-s');
		$dir_ips='127.0.0.1';
		///CEDULA NO EXISTE, INGRESA REGISTRO
		////INSERTAR REGISTRO
		$insertSQL = sprintf("INSERT INTO evento
		(fecha,
		hora,
		IP,
		nombre,
		apellido,
		correo,
		telefono,
		cedula,
		estado) 
		VALUES
		(
		'$fecha',
		'$hora',
		'$dir_ips',
		'$nombre',
		'$apellido',
		'$correo',
		'$celular',
		'$cedula',
		'1')");
		//echo"el insert es $insertSQL<br>";
		mysqli_select_db($conecta,$database_conecta);
		$Result1 = mysqli_query($conecta,$insertSQL) or die(mysql_error());
		$inserta=1;
		$texto='Tu inscripción se realizó correctamente';
		//mysqli_free_result($Result1);
		
		mysqli_select_db($conecta,$database_conecta);
		$query_vl_tit = "select id_registro from evento 
		where cedula='$cedula'";
		$vl_tit = mysqli_query($conecta,$query_vl_tit) or die(mysql_error());
		$row_vl_tit = mysqli_fetch_assoc($vl_tit);
		$totalRows_vl_tit = mysqli_num_rows($vl_tit);
		$id_llave=$row_vl_tit['id_registro'];
		mysqli_free_result($vl_tit);
	}
	else
	{
		$inserta=0;
		$texto='Ya te encuentras inscrito al evento';
		$id_llave=0;
	}
	return array($inserta,$texto,$id_llave);		
}

function enviar_correo_interno($id_registro)
{
	require('Connections/localhost.php');
	
	mysqli_select_db($conecta,$database_conecta);
	$query_titular = "SELECT * FROM referidos where id_registro='$id_registro'";
	$titular = mysqli_query($conecta,$query_titular) or die(mysql_error());
	$row_titular = mysqli_fetch_assoc($titular);
	$totalRows_titular = mysqli_num_rows($titular);
	
	$message .='<html> <body>';
	$message .='<table width="600" border="0" align="center">
  <tr>
    <td><img src="https://losolivosbogota.com/sites/referidos/imagenes/enca.jpg" width="600" height="216" /></td>
  </tr>
  <tr>
    <td><table width="550" border="0" align="center">
      <tr>
        <td colspan="2" align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
      
        <td colspan="2" align="center" valign="middle"><span style="color: #039468; font-size: 30px;">Llegó  una solicitud de referidos</span></td>
        </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><span style="color: #333333; font-size: 22px;">Una persona refirio a un conocido para adquirir el PLAN MED, Estos son los datos: </span></td>
        </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Fecha:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["fecha"].'</span></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Hora:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["hora"].'</span></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Nombre:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["nombre"].'</span></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Cedula:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["cedula"].'</span></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Celular:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["telefono"].'</span></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Correo:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'.$row_titular["correo"].'</span></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><hr></td>
        </tr>
      <tr>
        <td colspan="2" align="center" valign="middle"><span style="color: #333333; font-size: 22px;">Datos del Referido </span></td>
        </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Nombre:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["nombre_ref"].'</span></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Celular:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["celular_ref"].'</span></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Correo:</span></td>
        <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $row_titular["correo_ref"].'</span></td>
      </tr>
      <tr>
        <td colspan="2"><hr></td>
        </tr>
      <tr>
        <td colspan="2" align="center"><span style="color: #039468; font-size: 25px;">Por favor comunícate con esta persona y cuéntale porque somos la mejor opción del mercado.</span></td>
        </tr>
      <tr>
        <td colspan="2" align="center">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="https://losolivosbogota.com/sites/referidos/imagenes/footer.jpg" width="600" height="261" /></td>
  </tr>
</table>';
$message .='</body> </html>';
$para = "innovacion.bogota@losolivos.co,oscartga@gmail.com";
$titulo = "NUEVO REGISTRO DE REFERIDO";
$from = 'app@losolivosbogota.com';
$fromName = 'Los Olivos Digital';
$cabeceras = "De: $fromName"." <".$from.">";
$cabeceras .=  'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$enviado = mail($para, $titulo, $message, $cabeceras);
if ($enviado)
  {
	  //echo 'Email enviado correctamente';
  }else
  {
	  //echo 'Error en el envío del email';
   }
}
function enviar_correo_invitacion($id_registro)
{
	require('Connections/localhost.php');
	
	mysqli_select_db($conecta,$database_conecta);
	$query_titular = "SELECT * FROM evento where id_registro='$id_registro'";
	$titular = mysqli_query($conecta,$query_titular) or die(mysql_error());
	$row_titular = mysqli_fetch_assoc($titular);
	$totalRows_titular = mysqli_num_rows($titular);
	
	$message .='<html> <body>';
	$message .='<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><img src="https://losolivosbogota.com/felicidad/imagenes/Cabezote.png" width="600" height="153"></td>
  </tr>
  <tr>
    <td align="center" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle"><span style="color: #333333; font-size: 25px;">Estimado(a)'. $row_titular["nombre"].':</span></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><hr></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="middle"><span style="color: #333333; font-size: 22px;">Tu registro al show de la felicidad se realizó correctamente. Recuerda conectarte al evento 5 minutos antes haciendo clic en la siguiente imagen:</span></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><a href="https://youtu.be/rEF29LnKbds"><img src="https://losolivosbogota.com/felicidad/imagenes/correo.jpeg" width="600" height="777"></a></td>
  </tr>
  <tr>
    <td align="left" valign="middle"><img src="https://losolivosbogota.com/felicidad/imagenes/Footer.png" width="600" height="153"></td>
  </tr>
  </table>';
$message .='</body> </html>';
$para = "$row_titular[correo]";
$titulo = "Los Olivos te invita a disfutar del show de la felicidad";
$from = 'app@losolivosbogota.com';
$fromName = 'Los Olivos Digital';
$cabeceras = "De: $fromName"." <".$from.">";
$cabeceras .=  'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$enviado = mail($para, $titulo, $message, $cabeceras);
if ($enviado)
  {
	  //echo 'Email enviado correctamente';
  }else
  {
	  //echo 'Error en el envío del email';
   }
}
function datos_fallecido($ID_SQ)
{
	require('Connections/localhost.php');
	
	$ruta_actual=getcwd();
	$findme   = 'mobile';
	$pos = strpos($ruta_actual, $findme);
	
	if ($pos === false)
	{
		////esta en version de PC
		$ruta_imagen='';
		
	} 
	else
	{
		////esta en version de mobile
		$ruta_imagen='../';
	}
	
	mysqli_select_db($conecta,$database_conecta);
	$query_bs_da = "select id,
	nombreFallecido,
	fechaNacimiento, 
	fechaDeceso,
	sede,
	sala,
	lugarExequias,
	fechaExequias,
	destinoFinal,
	exhumaCementerio,
	perfil_fotoPrincipal 
	from obituariosProfiles where id='$ID_SQ'";
	$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
	$row_bs_da = mysqli_fetch_assoc($bs_da);
	$totalRows_bs_da = mysqli_num_rows($bs_da);
	
	if($row_bs_da['perfil_fotoPrincipal']=='')
	{
		$foto_ubica='<img src="'.$ruta_imagen.'imagenes/sin_imagen.png" width="120" height="120">';
	}
	else
	{
		$foto_ubica='<img src="'.$ruta_imagen.'imagenes/'.$row_bs_da['perfil_fotoPrincipal'].'" width="120" height="120">';
	}
	
	if($ruta_imagen=='')
	{
	
?>	
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
          <tr>
            <td colspan="2" align="center" valign="middle" class="titulos_cajasMB"><hr></td>
          </tr>
          <tr>
            <td width="40%" align="center" valign="middle" class="titulos_cajasMB"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" valign="middle"><?php echo $foto_ubica;?></td>
                </tr>
            </table></td>
            <td width="60%" align="center" valign="middle" class="titulos_cajasMB"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="3">
              <tr>
                <td align="left" valign="middle" class="titulo">Ser Querido:</td>
                <td  align="left" valign="middle" class="texto"><?php echo utf8_encode($row_bs_da['nombreFallecido']); ?> -QEPD-</td>
              </tr>
              <tr>
                <td width="27%" align="left" valign="middle" class="titulo">Sede:</td>
                <td width="73%"  align="left" valign="middle" class="texto"><?php echo utf8_encode($row_bs_da['sede']); ?></td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="titulo">Sala:</td>
                <td align="left" valign="middle" class="texto"><?php echo utf8_encode($row_bs_da['sala']); ?></td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="titulo">Fecha de Exequias:</td>
                <td align="left" valign="middle" class="texto"><?php  echo date('d/m/Y', $row_bs_da['fechaExequias']); ?> <?php date_default_timezone_set('America/Bogota');echo date('H:i', $row_bs_da['fechaExequias']); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="middle" class="titulos_cajasMB"><hr></td>
          </tr>
        </table>
<?php 	
	}
	if($ruta_imagen=='../')
	{
?>
<table width="101%" border="0" align="center" cellpadding="3" cellspacing="3">
          <tr>
            <td align="center" valign="middle" class="titulos_cajasMB"><hr></td>
          </tr>
          <tr>
            <td align="center" valign="middle" class="titulos_cajasMB"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="3">
              <tr>
                <td align="left" valign="middle" class="titulo"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" valign="middle"><?php echo $foto_ubica;?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="titulo">Ser Querido:</td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="texto"><span class="texto"><?php echo utf8_encode($row_bs_da['nombreFallecido']); ?> -QEPD-</span></td>
              </tr>
              <tr>
                <td width="27%" align="left" valign="middle" class="titulo">Sede:</td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="texto"><span class="texto"><?php echo utf8_encode($row_bs_da['sede']); ?></span></td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="titulo">Sala:</td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="texto"><span class="texto"><?php echo utf8_encode($row_bs_da['sala']); ?></span></td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="titulo">Fecha de Exequias:</td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="texto"><span class="texto"><?php echo date('d/m/Y', $row_bs_da['fechaExequias']); ?> <?php echo date('H:i', $row_bs_da['fechaExequias']); ?></span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" valign="middle" class="titulos_cajasMB"><hr></td>
          </tr>
        </table>
<?php 	
	}
}
function insertar_pago($nombre,
 $apellido,
 $cedula,
 $correo,
 $celular,
 $ciudad,
 $regimen,
 $razon,
 $sigla,
 $nit,
 $dv,
 $direccion_pagador,
 $tipo,
 $ID_SQ,
 $referenceCode,
 $foto,
 $nombre_arreglo,
 $description,
 $valor_total,
 $referenceCode_basico,
 $mensaje,
 $origen,
 $tipo_doc)
{
		require('Connections/localhost.php');
		//echo"entro al if 1<br>";
		////NO EXISTE REGISTRO SE DEBE INCLUIR
		
		mysqli_select_db($conecta,$database_conecta);
		$query_bs_da = "select referenceCode
		from confirma_pagos where referenceCode='$referenceCode'";
		$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
		$row_bs_da = mysqli_fetch_assoc($bs_da);
		$totalRows_bs_da = mysqli_num_rows($bs_da);
		
		if($totalRows_bs_da==0)
		{
			////no existe la referencia unica
			date_default_timezone_set('America/Bogota');
			$fecha_inserta=date('Y-m-d H:i:s');
			if($tipo=='1')
			{
				$tipo='Arreglo floral';
			}
			if($tipo=='2')
			{
				$tipo='Bono';
			}
			$insertSQL = sprintf("INSERT INTO confirma_pagos
			(
			referenceCode,
			response_code_pol,
			phone,
			additional_value,
			test,
			transaction_date,
			cc_number,
			cc_holder,
			error_code_bank,
			billing_country,
			bank_referenced_name,
			description,
			administrative_fee_tax,
			administrative_fee,
			payment_method_type,
			office_phone,
			email_buyer,
			response_message_pol,
			error_message_bank,
			shipping_city,
			transaction_id,
			sign,
			tax,
			payment_method,
			billing_address,
			payment_method_name,
			pse_bank,
			state_pol,
			date,
			nickname_buyer,
			reference_pol,
			currency,
			risk,
			shipping_address,
			bank_id,
			payment_request_state,
			customer_number,
			administrative_fee_base,
			attempts,
			merchant_id,
			exchange_rate,
			shipping_country,
			installments_number,
			franchise,
			payment_method_id,
			extra1,
			extra2,
			antifraudMerchantId,
			extra3,
			nickname_seller,
			ip,
			airline_code,
			billing_city,
			pse_reference1,
			reference_sale,
			pse_reference3,
			pse_reference2,
			nombre,
			apellido,
			tipo_doc,
			cedula,
			correo,
			celular,
			ciudad,
			direccion_pagador,
			tipo,
			regimen,
			razon,
			sigla,
			nit,
			id_obituarios_profile,
			id_ofrendas,
			valor_pagado,
			fecha_creacion,
			origen,
			descripcion) 
			VALUES
			(
			'$referenceCode',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'0',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'$nombre',
			'$apellido',
			'$tipo_doc',
			'$cedula',
			'$correo',
			'$celular',
			'$ciudad',
			'$direccion_pagador',
			'$tipo',
			'$regimen',
			'$razon',
			'$sigla',
			'$nit',
			'$ID_SQ',
			'$referenceCode_basico',
			'$valor_total',
			'$fecha_inserta',
			'$origen',
			'$description')");
			//echo"$insertSQL<br>";
			mysqli_select_db($conecta,$database_conecta);
			$Result2 = mysqli_query($conecta,$insertSQL) or die(mysql_error());
			if($Result2)
			{
				$inserta=1;
			}
			else
			{
				$inserta=0;
			}
			if($mensaje<>'')
			{
				$fecha_actual = date("Y-m-d");
				$fecha_inicio= strtotime($fecha_actual); 
				$nombre_da_condolen=$nombre." ".$apellido;
				/////INSERTA EL MENSAJE EN TABLA DE CONDOLENCIAS
				$insertSQL = sprintf("INSERT INTO mensajesCondolencia
				(
				createdAt,
				updatedAt,
				obituarioProfile,
				nombre,
				email,
				telefono,
				mensaje,
				imagen,
				status) 
				VALUES
				(
				'$fecha_inicio',
				'$fecha_inicio',
				'$ID_SQ',
				'$nombre_da_condolen',
				'$referenceCode',
				'$celular',
				'$mensaje',
				'0.png',
				'2')");
				//echo"$insertSQL<br>";
				mysqli_select_db($conecta,$database_conecta);
				$Result1 = mysqli_query($conecta,$insertSQL) or die(mysql_error());
			}
		}
		else
		{
			$inserta=0;
		}
		return $inserta;
}
function verifica_insercion($referenceCode)
{
		require('Connections/localhost.php');
		mysqli_select_db($conecta,$database_conecta);
		$query_bs_da = "select referenceCode
		from confirma_pagos where referenceCode='$referenceCode'";
		$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
		$row_bs_da = mysqli_fetch_assoc($bs_da);
		$totalRows_bs_da = mysqli_num_rows($bs_da);
		
		if($totalRows_bs_da==1)
		{
			$valida=1;
		}
		else
		{
			$valida=0;
		}
		return $valida;
}
function error_insercion($referenceCode,
				$nombre,
				$apellido,
				$tipo_doc,
				$cedula,
				$correo,
				$celular,
				$ciudad,
				$direccion_pagador,
				$tipo,
				$regimen,
				$razon,
				$sigla,
				$nit,
				$ID_SQ,
				$referenceCode_basico,
				$valor_total,
				$fecha_inserta,
				$origen,
				$description,
				$mensaje)
{
	
	/////funcion cuando la insercion no se realiza correctamente
		    $cuerpo_msj='';
		$cuerpo_msj .='<html> <body>';
		$cuerpo_msj .='<table width="800" border="0" align="center">
		<tr>
		<td><img src="https://losolivosbogota.com/sites/prevision/imagenes/encabezado.png" width="800" height="159" /></td>
		</tr>
		<tr>
		<td><table width="800" border="0" align="center">
		<tr>
		<td colspan="2" align="center" valign="middle">&nbsp;</td>
		</tr>
		<tr>
		
		<td colspan="2" align="center" valign="middle"><span style="color: #039468; font-size: 30px;">ERROR EN EL REGISTRO DE UNA COMPRA-PAGO</span></td>
		</tr>
		<tr>
		<td colspan="2" align="left" valign="middle"><span style="color: #333333; font-size: 22px;">se recibio un mensaje de condolencia a traves de nuestro portal Web, los datos son: </span></td>
		</tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">fecha_inserta:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $fecha_inserta.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">referenceCode:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $referenceCode.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">nombre:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $nombre.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">apellido:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $apellido.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">tipo_doc:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $tipo_doc.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">cedula:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $cedula.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">correo:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $correo.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">celular:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $celular.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">ciudad:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'.$ciudad.'</span></td>
		  </tr>
		<tr>
		<td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">direccion_pagador:</span></td>
		<td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $direccion_pagador.'</span></td>
		</tr>
		<tr>
		<td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">tipo:</span></td>
		<td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $tipo.'</span></td>
		</tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">regimen:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $regimen.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">razon:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $razon.'</span></td>
		  </tr>
		<tr>
		<td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">sigla:</span></td>
		<td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $sigla.'</span></td>
		</tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">nit:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $nit.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">ID_SQ:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $ID_SQ.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">referenceCode_basico:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $referenceCode_basico.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">valor_total:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'.$valor_total.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">fecha_inserta:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $fecha_inserta.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">origen:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $origen.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">description:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $description.'</span></td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="middle"><hr></td>
		  </tr>
		</table></td>

		</tr>
		<tr>
		<td><img src="https://losolivosbogota.com/sites/prevision/imagenes/footer_pasarela.png" width="800" height="233"" /></td>
		</tr>
		</table>';
		$cuerpo_msj .='</body> </html>';
	$para = "innovacion.bogota@losolivos.co";
	$titulo = "ERROR AL REGISTRAR UNA COMPRA - PAGO";
	$from = 'app@losolivosbogota.com';
	$fromName = 'Los Olivos Digital';
	$cabeceras = "De: $fromName"." <".$from.">";
	$cabeceras .=  'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$enviado = mail($para, $titulo, $cuerpo_msj, $cabeceras);
}
function registrar($origen,$respuesta)
{
	require('Connections/localhost.php');
	date_default_timezone_set('America/Bogota');
	$fecha=date('Y-m-d H:i:s');	
	$insertSQL = sprintf("INSERT INTO controlWS
	(fecha,
	origen,
	dato) 
	VALUES
	('$fecha',
	'$origen',
	'$respuesta')");
	mysqli_select_db($conecta,$database_conecta);
	$Result1 = mysqli_query($conecta,$insertSQL) or die(mysql_error());	
}
function datos_conexion($origen,$referenceCode,$ID_SQ)
{
	date_default_timezone_set('America/Bogota');
	if($origen=='pagos')
	{
		$nuevoreferenceCode=rand(100,10000).date('sHdmi');
	}
	if($origen=='compras')
	{
		//$nuevoreferenceCode=$referenceCode.date('sHdmi').$ID_SQ;
		$nuevoreferenceCode=rand(1,1000).date('sHi').$ID_SQ;

	}
	
	/*
	/////LINK DE PRUEBAS
	$link='https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu';
	$merchantId='508029';
	$apiKey='4Vj8eK4rloUd272L48hsrarnUA';
	$accountId='512321';
	$test='1';
	$currency='COP';
	*/
	
	////LINK DE PRODUCCION
	$link='https://checkout.payulatam.com/ppp-web-gateway-payu/';
	$merchantId='98160';
	$apiKey='13d124bd020';
	$accountId='102202';
	$test='0';
	$currency='COP';
	return array($nuevoreferenceCode,$link,$merchantId,$apiKey,$accountId,$test,$currency);
}
function encabezado_pagos()
{
	$ruta_actual=getcwd();
	$findme   = 'mobile';
	$pos = strpos($ruta_actual, $findme);
	
	if ($pos === false)
	{
		////esta en version de PC
		$ruta_imagen='<img src="../imagenes/enca_pagos.png"/>';
	} 
	else
	{
		////esta en version de mobile
		$ruta_imagen='<img src="../../imagenes/enca_pagos.png"/>';
	}	
?>	
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><?php echo $ruta_imagen;?></td>
</tr>
</table>	
<?php	
}
function encabezado()
{
	$ruta_actual=getcwd();
	$findme   = 'mobile';
	$pos = strpos($ruta_actual, $findme);
	
	if ($pos === false)
	{
		////esta en version de PC
		$ruta_imagen='<img src="imagenes/banner-03.png"/>';
	} 
	else
	{
		////esta en version de mobile
		$ruta_imagen='<img src="../imagenes/banner-03.png"/>';
	}	
?>	
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><?php echo $ruta_imagen;?></td>
</tr>
</table>
	
<?php	
}
function footer()
{
	$ruta_actual=getcwd();
	$findme   = 'mobile';
	$pos = strpos($ruta_actual, $findme);
	
	if ($pos === false)
	{
		////esta en version de PC
		$ruta_imagen='<img src="imagenes/footer.png"/>';
	} 
	else
	{
		////esta en version de mobile
		$ruta_imagen='<img src="../imagenes/footer.png"/>';
	}	
?>	
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><?php echo $ruta_imagen;?></td>
</tr>
</table>
	
<?php	
}
function footer_pagos()
{
	$ruta_actual=getcwd();
	$findme   = 'mobile';
	$pos = strpos($ruta_actual, $findme);
	
	if ($pos === false)
	{
		////esta en version de PC
		$ruta_imagen='<img src="../imagenes/footer_pagos.png"/>';
	} 
	else
	{
		////esta en version de mobile
		$ruta_imagen='<img src="../../imagenes/footer_pagos.png"/>';
	}	
?>	
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><?php echo $ruta_imagen;?></td>
</tr>
</table>
	
<?php	
}
function valida_horario($ID_SQ)
{
	require('Connections/localhost.php');
	mysqli_select_db($conecta,$database_conecta);
	$query_bs_da = "select fechaExequias
	from obituariosProfiles where id='$ID_SQ'";
	$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
	$row_bs_da = mysqli_fetch_assoc($bs_da);
	$totalRows_bs_da = mysqli_num_rows($bs_da);
	
	date_default_timezone_set('America/Bogota');
	$fecha_actual=strtotime("now");
	$fecha_d_exequias=$row_bs_da['fechaExequias'];

	$fecha_cotrol_flores=$fecha_actual+7200;///suma 2 horas a la fecha actual
	$fecha_cotrol_bonos=$fecha_actual+1200;///suma 20 min a la fecha actual
	$diferencia_flores=$fecha_cotrol_flores-$fecha_d_exequias;
	$diferencia_bonos=$fecha_cotrol_bonos-$fecha_d_exequias;
	if($diferencia_flores<0)
	{
		$permite_flores='SI';
	}
	else
	{
		$permite_flores='NO';
	}
	if($diferencia_bonos<0)
	{
		$permite_bonos='SI';
	}
	else
	{
		$permite_bonos='NO';
	}
	//echo"la diferencia de fechas es de $diferencia<br>";
	
	return array($permite_flores,$permite_bonos);
}
function insertar_mensaje($mensaje,$nombre_da_condolen,$correo,$celular,$ID_SQ)
{
			require('Connections/localhost.php');
			date_default_timezone_set('America/Bogota');
			$fecha_actual = date("Y-m-d");
			$fecha_inicio= strtotime($fecha_actual); 
			/////INSERTA EL MENSAJE EN TABLA DE CONDOLENCIAS
			$insertSQL = sprintf("INSERT INTO mensajesCondolencia
			(
			createdAt,
			updatedAt,
			obituarioProfile,
			nombre,
			email,
			telefono,
			mensaje,
			imagen,
			status) 
			VALUES
			(
			'$fecha_inicio',
			'$fecha_inicio',
			'$ID_SQ',
			'$nombre_da_condolen',
			'$correo',
			'$celular',
			'$mensaje',
			'0.png',
			'2')");
			mysqli_select_db($conecta,$database_conecta);
			$Result1 = mysqli_query($conecta,$insertSQL) or die(mysql_error());
			
			notificar_mensaje($mensaje,$nombre_da_condolen,$correo,$celular,$ID_SQ);
			echo"MENSAJE ENVIADO CORRECTAMENTE";
}
function notificar_mensaje($mensaje,$nombre_da_condolen,$correo,$celular,$ID_SQ)
{
	/////NOTIFICA UN MENSAJE DE CONDOLENCIA
	require('Connections/localhost.php');
	date_default_timezone_set('America/Bogota');
	mysqli_select_db($conecta,$database_conecta);
	$query_bs_da = "select id, nombreFallecido,sede,sala,fechaExequias from obituariosProfiles where id='$ID_SQ'";
	//echo"$query_bs_da<br>";
	$bs_da = mysqli_query($conecta,$query_bs_da) or die(mysql_error());
	$row_bs_da = mysqli_fetch_assoc($bs_da);
	$totalRows_bs_da = mysqli_num_rows($bs_da);
	$fecha=date("Y-m-d");
	$hora=date('H:i:s');
	$orden=$row_bs_da['id'];
	$fallecido=$row_bs_da['nombreFallecido'];
	$sede=$row_bs_da['sede'];
	$sala=$row_bs_da['sala'];
	$fecha_exe=date('d/m/Y', $row_bs_da['fechaExequias']);
	$hora_exe=date('H:i', $row_bs_da['fechaExequias']);
	//echo"sede es $sede<br>";
	$correos_enviar=listado_destinatarios($sede); 
	//echo"correos_enviar es $correos_enviar<br>";
	    $cuerpo_msj='';
		$cuerpo_msj .='<html> <body>';
		$cuerpo_msj .='<table width="600" border="0" align="center">
		<tr>
		<td><img src="https://losolivosbogota.com/sites/prevision/imagenes/encabezado.png" width="800" height="159"" /></td>
		</tr>
		<tr>
		<td><table width="550" border="0" align="center">
		<tr>
		<td colspan="2" align="center" valign="middle">&nbsp;</td>
		</tr>
		<tr>
		
		<td colspan="2" align="center" valign="middle"><span style="color: #039468; font-size: 30px;">MENSAJE DE CONDOLENCIA RECIBIDO A TRAVES DE PORTAL WEB</span></td>
		</tr>
		<tr>
		<td colspan="2" align="left" valign="middle"><span style="color: #333333; font-size: 22px;">se recibio un mensaje de condolencia a traves de nuestro portal Web, los datos son: </span></td>
		</tr>
		<tr>
		<td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Fecha:</span></td>
		<td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $fecha.'</span></td>
		</tr>
		<tr>
		<td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Hora:</span></td>
		<td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $hora.'</span></td>
		</tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Orden:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $orden.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Ser querido:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $fallecido.'</span></td>
		  </tr>
		<tr>
		<td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Sede:</span></td>
		<td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $sede.'</span></td>
		</tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Sala:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $sala.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Fecha Exequias:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $fecha_exe.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Hora Exequias:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $hora_exe.'</span></td>
		  </tr>
		<tr>
		<td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Mensaje:</span></td>
		<td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'.$mensaje.'</span></td>
		</tr>
		<tr>
		  <td colspan="2" align="left" valign="middle"><hr></td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="middle"><span style="color: #333333; font-size: 22px;">Datos de quien envia el mensaje</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle">&nbsp;</td>
		  <td align="left" valign="middle">&nbsp;</td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Nombre:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $nombre_da_condolen.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Correo Electrónico:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $correo.'</span></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle"><span style="color: #039468; font-size: 16px;">Celular:</span></td>
		  <td align="left" valign="middle"><span style="color: #333333; font-size: 16px;">'. $celular.'</span></td>
		  </tr>
		 
		<tr>
		  <td colspan="2"><hr></td>
		  </tr>
		<tr>
		<td colspan="2" align="center">&nbsp;</td>
		</tr>
		<tr>
		<td colspan="2" align="center">&nbsp;</td>
		</tr>
		</table></td>
		</tr>
		<tr>
		<td><img src="https://losolivosbogota.com/sites/prevision/imagenes/footer_pasarela.png" width="800" height="233"" /></td>
		</tr>
		</table>';
		$cuerpo_msj .='</body> </html>';
	$para = $correos_enviar;
	$titulo = "Ha llegado un mensaje de condolencia";
	$from = 'app@losolivosbogota.com';
	$fromName = 'Los Olivos Digital';
	$cabeceras = "De: $fromName"." <".$from.">";
	$cabeceras .=  'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$enviado = mail($para, $titulo, $cuerpo_msj, $cabeceras);
	if ($enviado)
	{
		//echo 'Email enviado correctamente';
	}
	else
	{
		//echo 'Error en el envío del email';
	}
	
}
//@JohanGutierrez, modificación de validación si la función ya se encuentra declarada
if(!function_exists('listado_destinatarios')){
	function listado_destinatarios($sede_obj)
	{
		//echo"sede es $sede_obj<br>";
		$correos_enviar='';
		$correos_enviar.='innovacion.bogota@losolivos.co';
													
		if($sede_obj=='SAN DIEGO')
		{
			$correos_enviar.=',sandiego@coopserfun.com.co,casandiego@coopserfun.com.co,jefecandelaria@coopserfun.com.co,jefecandelaria@coopserfun.com.co';
		}
		if($sede_obj=='CHICO')
		{
			$correos_enviar.=',chico@coopserfun.com.co,cachico@coopserfun.com.co,jefecandelaria@coopserfun.com.co';
		}
		if($sede_obj=='PALERMO')
		{
			$correos_enviar.=',jpalermo.bogota@losolivos.co,cajap.bogota@losolivos.co,palermo.bogota@losolivos.co';
		}
		if($sede_obj=='RESTREPO')
		{
			$correos_enviar.=',dirolivossur.bogota@losolivos.co,restrepo.bogota@losolivos.co,cajar.bogota@losolivos.co';
		}
		if($sede_obj=='TEUSAQUILLO')
		{
			$correos_enviar.=',teusaquillo.bogota@losolivos.co,cajat.bogota@losolivos.co,jpalermo.bogota@losolivos.co';
		}
		if($sede_obj=='JPCLO')
		{
			$correos_enviar.=',jpclo.bogota@losolivos.co,cajajpclo.bogota@losolivos.co,jparque.bogota@losolivos.co';
		}
		if($sede_obj=='SOACHA')
		{
			$correos_enviar.=',soacha.bogota@losolivos.co,dirolivossur.bogota@losolivos.co';
		}
		
		return $correos_enviar;
	}
}
function define_referencia($descripcion)
{
	///define el nombre de la referencia y descripcion de acuerdo al tipo de servicio a pagar
	
	if($descripcion=='excedente')
	{
		$referencia='No. de orden de servicio';
		$texto_referencia='Haz seleccionado excedente de servicio funerario, recuerda ingresar en la referencia de pago el <strong>número de orden de servicio del homenaje</strong>. Si no conoces este número, solicitalo en la sede o a través de nuestro Callcenter marcando desde tu celular<strong> #317. </strong>';
	}
	if($descripcion=='servicio')
	{
		$referencia='No. de orden de servicio';
		$texto_referencia='Haz seleccionado pagar un servicio funerario, recuerda ingresar en la referencia de pago el <strong>número de orden de servicio del homenaje</strong>. Si no conoces este número, solicitalo en la sede o a través de nuestro Callcenter marcando desde tu celular<strong> #317. </strong>';
	}
	if($descripcion=='prevision')
	{
		$referencia='Ingresa el numero de documento del titular del plan';
		$texto_referencia='Estas intentando pagar un plan de previsión exequial, utiliza esta opción si tu plan de previsión es de <strong>Bogotá, Cundinamarca o Boyacá</strong>. Si tu afiliación se realizó a través de otra ciudad ingresa a este <a href="https://losolivos.co" target="blank" class="alert-link">link</a> y selecciona la ciudad correspondiente.';
	}
		
	if($descripcion=='prenecesidad')
	{
		$referencia='No. documento del contratante';
		$texto_referencia='Estas intentando pagar una prenecesidad, recuerda colocar en la referencia de pago el número de identificación del tomador de la prenecesidad';
	}
	return array($referencia,$texto_referencia);
}
function curl_get_file_contentsNW ($URL) {
    $curl = curl_init( );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $URL);
    $contents = curl_exec($curl);
    curl_close($curl);

    return $contents ? $contents : FALSE;
}
function verificar_afiliacion($cedula)
{
	////verifica si la cedula ingresada existe en SAP	
	////web services que consulta los planes de una cedula en SAP
	$WS3="http://170.239.154.35:8000/OLIVOS_WEB_SERVICES/Modelo/Controller.xsjs?cmd=PRODUCTO_PREVISION_BASICO&bd=OLV_BOGOTA&id=";
	$wplan=$WS3.$cedula;
	$wplan1=curl_get_file_contentsNW($wplan);
	$data_PLA =json_decode($wplan1,true);
	$cantidad_planes=count($data_PLA);
	if($cantidad_planes==0)
	{
		////no encontro planes
		$texto='ATENCIÓN: no se encontraron planes de previsión exequial con el documento de identificación ingresado';
		$resultado='0';
	}
	else
	{
		$texto='PLANES ENCONTRADOS';
		$resultado='1';
	}
	//echo"resultado es linea 6850 $resultado y testo es $texto<br>";
	return array($resultado,$texto); 
}
?>