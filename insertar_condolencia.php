<?php
require_once 'Connections/db.php';

date_default_timezone_set('America/Bogota');

$ok = false;
$error = '';
$obituarioID = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $obituarioID = intval($_POST['obituarioProfile'] ?? 0);
    $nombre      = trim($_POST['nombre'] ?? '');
    $email       = trim($_POST['correo'] ?? '');
    $telefono    = trim($_POST['celular'] ?? '');
    $mensaje     = trim($_POST['mensaje'] ?? '');

    if ($obituarioID && $nombre && $mensaje) {

        try {
            $now = time();

            $sql = "
                INSERT INTO mensajesCondolencia
                (
                    createdAt,
                    updatedAt,
                    obituarioProfile,
                    nombre,
                    email,
                    telefono,
                    mensaje,
                    imagen,
                    status
                )
                VALUES
                (
                    :createdAt,
                    :updatedAt,
                    :obituarioProfile,
                    :nombre,
                    :email,
                    :telefono,
                    :mensaje,
                    :imagen,
                    :status
                )
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':createdAt'        => $now,
                ':updatedAt'        => $now,
                ':obituarioProfile' => $obituarioID,
                ':nombre'           => $nombre,
                ':email'            => $email,
                ':telefono'         => $telefono,
                ':mensaje'          => $mensaje,
                ':imagen'           => '0.png',
                ':status'           => 1
            ]);

            $ok = true;

            // ===== ENVIAR CORREO CON PDF =====
            require_once 'funciones_pdf.php';
            
            // Ajusta la ruta a donde tengas PHPMailer
            require_once 'PHPMailer-master/PHPMailer-master/src/Exception.php';
            require_once 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
            require_once 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {
                // Generar PDF en memoria (String)
                $pdfContent = generar_pdf_condolencias($obituarioID, 'S');

                // Configuración del servidor (SMTP)
                // $mail->SMTPDebug = 2;                      // Habilitar salida de depuración detallada
                $mail->isSMTP();                                            // Enviar usando SMTP
                $mail->Host       = 'smtp.example.com';                     // Configurar el servidor SMTP
                $mail->SMTPAuth   = true;                                   // Habilitar autenticación SMTP
                $mail->Username   = 'user@example.com';                     // Nombre de usuario SMTP
                $mail->Password   = 'secret';                               // Contraseña SMTP
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Encriptación TLS implícita
                $mail->Port       = 587;                                    // Puerto TCP

                // Destinatarios
                $mail->setFrom('from@example.com', 'Mailer');
                $mail->addAddress('recepient@example.com', 'User');     // Añadir un destinatario

                // Adjuntos
                if ($pdfContent) {
                    $mail->addStringAttachment($pdfContent, 'Libro_de_Firmas.pdf');
                }

                // Contenido
                $mail->isHTML(true);                                  // Establecer formato de correo a HTML
                $mail->Subject = 'Nuevo mensaje en Libro de Firmas';
                $mail->Body    = 'Se ha registrado un nuevo mensaje. Adjunto encontrarás el PDF actualizado.';
                $mail->AltBody = 'Se ha registrado un nuevo mensaje. Adjunto encontrarás el PDF actualizado.';

                $mail->send();
                // echo 'El mensaje ha sido enviado';
            } catch (Exception $e) {
                // echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
                // No detenemos el flujo si falla el correo, pero podríamos registrar el error.
            }


        } catch (PDOException $e) {
            $error = 'No fue posible guardar el mensaje.';
        }

    } else {
        $error = 'Faltan datos obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mensaje</title>

<style>
body{
    margin:0;
    background:#f5f5f5;
    font-family:Arial, Helvetica, sans-serif;
}
.box{
    max-width:600px;
    margin:90px auto;
    background:#fff;
    padding:40px;
    border-radius:15px;
    text-align:center;
}
.ok{color:#007E7E;}
.err{color:#b00020;}
</style>

<?php if ($ok): ?>
<script>
setTimeout(() => {
    window.location.href = "form.php?id=<?= $obituarioID ?>";
}, 4000);
</script>
<?php endif; ?>

</head>
<body>

<div class="box">
<?php if ($ok): ?>
    <h2 class="ok">Gracias por tu mensaje</h2>
    <p>
        Tu mensaje fue enviado correctamente.<br>
    </p>
<?php else: ?>
    <h2 class="err">No fue posible enviar el mensaje</h2>
    <p><?= htmlspecialchars($error) ?></p>
    <a href="javascript:history.back()">Volver</a>
<?php endif; ?>
</div>

</body>
</html>