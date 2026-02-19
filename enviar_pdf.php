<?php
require_once 'funciones_pdf.php';

// PHPMailer source files
require_once 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require_once 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$ID_SQ = intval($_GET['ID_SQ'] ?? 0);

if (!$ID_SQ) {
    die("ID de obituario no proporcionado.");
}

$mail = new PHPMailer(true);

try {
    // Generar PDF en memoria (String)
    $pdfContent = generar_pdf_condolencias($ID_SQ, 'S');

    if (!$pdfContent) {
        die("Error al generar el PDF o no hay mensajes.");
    }
    $mail->isSMTP();                                             //Send using SMTP
	$mail->CharSet = 'UTF-8';
	$mail->ContentType = 'text/html' . "\r\n";
	$mail->Host       = 'mail.losolivosbogota.com';              //Set the SMTP server to send through
	$mail->SMTPAuth   = true;                                    //Enable SMTP authentication
	$mail->Username   = 'app2@losolivosbogota.com';              //SMTP username
	$mail->Password   = 'Colombia22++';
	$mail->SMTPSecure = 'ssl';                                   //SMTP password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;             //Enable implicit TLS encryption
	$mail->Port       = 465;
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

    // Destinatarios
    $mail->setFrom('app@losolivosbogota.com', 'Los Olivos Digital');
    $mail->addAddress('dev.bogota@losolivos.co', 'Usuario');      // Destinatario

    // Adjuntos
    $mail->addStringAttachment($pdfContent, 'Libro_de_Firmas.pdf');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Envio Manual - Libro de Firmas';
    $mail->Body    = "<!DOCTYPE html>";
    $message .= "<html><body>";

    $message .= '<table width="600" border="0" align="center">
				<tr>
				<td><img src="./assets/MAILING.jpg" width="800" height="118" /></td>
				</tr>	
				</table>';


        $mail->Body    = $message;

    $mail->send();
    echo "El correo con el PDF ha sido enviado correctamente.";

} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
}
