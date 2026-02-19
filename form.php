<?php
declare(strict_types=1);

date_default_timezone_set('America/Bogota');

/* ===============================
   VALIDAR ID DEL SER QUERIDO
================================ */
$obituarioProfile = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($obituarioProfile <= 0) {
    die('Servicio no válido');
}

/* ===============================
   PROCESAR FORMULARIO
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $obituarioProfile = ($_POST['obituarioProfile'] ?? 0);
    $nombre  = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // $nombre   = trim($_POST['nombre'] ?? '');
    // $email   = trim($_POST['email'] ?? '');
    // $telefono  = trim($_POST['telefono'] ?? '');
    // $mensaje  = trim($_POST['mensaje'] ?? '');
    // $obituarioProfile = (int)($_POST['obituarioProfile'] ?? 0);

    if ($nombre !== '' && $mensaje !== '' && $obituarioProfile > 0) {

        /* ===== CONEXIÓN BD ===== */
        $hostname_conecta = "92.42.111.41";
        $database_conecta = "losolivo_portal";
        $username_conecta = "losolivo_usrport";
        $password_conecta = "+35M7gIcUd9oAV*DFi";

        try {
            $dsn = "mysql:host=$hostname_conecta;dbname=$database_conecta;charset=utf8mb4";
            $pdo = new PDO($dsn, $username_conecta, $password_conecta, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            /* ===== INSERTAR MENSAJE ===== */
            $sql = "
                INSERT INTO mensajesCondolencia
                (obituarioProfile, nombre, email, telefono, mensaje)
                VALUES
                (:obituarioProfile, :nombre, :email, :telefono, :mensaje)
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id'      => $obituarioProfile,
                ':nombre'  => $nombre,
                ':email'  => $email,
                ':telefono' => $telefono,
                ':mensaje' => $mensaje
            ]);

            $enviado = true;

        } catch (PDOException $e) {
            die("Error al guardar el mensaje");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mensaje de Condolencia</title>

<style>
/* 🔒 DISEÑO ORIGINAL — NO MODIFICADO */
* {
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}
body {
    margin: 0;
    background: #f5f5f5;
}
.banner {
    background: url("assets/LIBRO DE FIRMAS GRANDE_Mesa de trabajo 1-02.webp") center/cover no-repeat;
    color: #fff;
    padding: 60px 20px;
    text-align: center;
    position: relative;
}
.banner::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
}
.banner h1 {
    position: relative;
    z-index: 1;
    font-size: 1.8rem;
    max-width: 900px;
    margin: auto;
}
.container {
    background: #fff;
    max-width: 1100px;
    margin: -40px auto 40px;
    padding: 30px;
    border-radius: 10px;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
input, textarea {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 1rem;
}
textarea {
    min-height: 160px;
    resize: vertical;
}
.btn {
    background: #000;
    color: #fff;
    padding: 12px 30px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-size: 1rem;
}
.btn:hover {
    opacity: 0.9;
}
.section-title {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}
.section-title img {
    width: 40px;
}
.logo {
    text-align: center;
}
.logo img {
    max-width: 160px;
}
</style>
</head>

<body>

<div class="banner">
    <h1>La luz de nuestros seres queridos permanece en nuestros corazones y en cada detalle</h1>
</div>

<br><br><br>

<div class="container">

<?php if (!empty($enviado)): ?>
    <h2>Gracias por tu mensaje de condolencia</h2>
    <p>Tu mensaje ha sido enviado correctamente.</p>
<?php else: ?>

    <div class="form-grid">

        <div>
            <div class="section-title">
                <img src="assets/LIBRO DE FIRMAS GRANDE_Mesa de trabajo 1-03.webp">
                <h2>Nombre del Ser Querido</h2>
            </div>

            <form method="POST"  action="insertar_condolencia.php">
                <!-- ID DEL SER QUERIDO -->
                <input type="hidden" name="obituarioProfile" value="<?php echo $_GET['id'] ?>">

                <label>Nombre</label>
                <input 
    type="text" 
    name="nombre" 
    required
    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
    title="Solo se permiten letras"
    required
>

                <label style="margin-top:15px;">email Electrónico</label>
                <input type="email" name="email">

                <label style="margin-top:15px;">telefono</label>
                <input 
    type="tel" 
    name="telefono"
    maxlength="10"
    pattern="[0-9]{10}"
    title="Debe contener exactamente 10 números y no letras"
    required
    >
        </div>

        <div>
            <div class="logo">
                <img src="assets/LIBRO DE FIRMAS GRANDE_Mesa de trabajo 1-04.webp">
            </div>

            <h2 style="margin-top:30px;">Mensaje de Condolencia</h2>

            <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>

            <br><br>
            <button class="btn" type="submit">Enviar Mensaje</button>
            </form>
        </div>

    </div>

<?php endif; ?>

</div>
</body>
</html>
