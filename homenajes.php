<?php
declare(strict_types=1);

date_default_timezone_set('America/Bogota');
require_once 'Connections/db.php';

/* ========= REGLA DE HORAS ========= */
$fecha_inicio = time();                 // ahora
$fecha_fin    = strtotime('+2 days');   // ahora + 2 días

/* ========= SEDES DISPONIBLES ========= */
$sqlSedes = "
SELECT DISTINCT sede
FROM obituariosProfiles
WHERE fechaExequias BETWEEN :inicio AND :fin
AND sede NOT IN ('DIRECCION GENERAL', 'CHICO', 'SAN DIEGO')
ORDER BY sede ASC
";
$stmtSedes = $pdo->prepare($sqlSedes);
$stmtSedes->bindValue(':inicio', $fecha_inicio, PDO::PARAM_INT);
$stmtSedes->bindValue(':fin', $fecha_fin, PDO::PARAM_INT);
$stmtSedes->execute();
$sedes = $stmtSedes->fetchAll(PDO::FETCH_COLUMN);

/* ========= LISTADO PRINCIPAL ========= */
$sql = "
SELECT
  id,
  nombreFallecido,
  fechaExequias,
  sede,
  sala,
  perfil_fotoPrincipal
FROM obituariosProfiles
WHERE fechaExequias BETWEEN :inicio AND :fin
AND sede NOT IN ('DIRECCION GENERAL', 'CHICO', 'SAN DIEGO')
AND sala NOT IN (
  'SALA 19 RESTREPO DIRECTO',
  'SALA 18 RESTREPO LOCAL',
  'LOCAL SOACHA',
  'SALA 11 TEUSAQUILLO DIRECTO',
  ''
)
";

if (!empty($_GET['sede'])) {
  $sql .= " AND sede = :sede";
}

$sql .= " ORDER BY fechaExequias ASC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':inicio', $fecha_inicio, PDO::PARAM_INT);
$stmt->bindValue(':fin', $fecha_fin, PDO::PARAM_INT);

if (!empty($_GET['sede'])) {
  $stmt->bindValue(':sede', $_GET['sede'], PDO::PARAM_STR);
}

$stmt->execute();
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Homenajes en Sala</title>

<style>
*{
    box-sizing:border-box;
    font-family: Arial, Helvetica, sans-serif;
}
body{
    margin:0;
    background:#f5f5f5;
}
.selector-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin: 40px auto 0;
  text-align: center;
}

.selector-wrapper label {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 12px;
  color: #333;
}

.selector-wrapper select {
  width: 320px;
  max-width: 90%;
  padding: 14px 18px;
  font-size: 16px;
  border-radius: 12px;
  border: 1px solid #ccc;
  background-color: #fff;
  cursor: pointer;
  transition: all 0.3s ease;
  appearance: none;
  background-image: url("data:image/svg+xml;utf8,<svg fill='%23666' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M5.5 7.5l4.5 4.5 4.5-4.5'/></svg>");
  background-repeat: no-repeat;
  background-position: right 16px center;
  background-size: 18px;
}

.selector-wrapper select:hover {
  border-color: #999;
}

.selector-wrapper select:focus {
  outline: none;
  border-color: #6c63ff;
  box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.15);
}

/* ===== BANNER ===== */
.banner{
    background:url("assets/LIBRO DE FIRMAS GRANDE_Mesa de trabajo 1-02.webp") center/cover no-repeat;
    padding:70px 20px;
    position:relative;
    text-align:center;
    color:#fff;
}
.banner::after{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.55);
}
.banner h1{
    position:relative;
    z-index:1;
}

/* ===== CONTAINER ===== */
.container{
    max-width:1200px;
    margin:-50px auto 50px;
    padding:20px;
}

/* ===== GRID ===== */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(260px,1fr));
    gap:25px;
    padding: 5%;
}

/* ===== CARD ===== */
.card{
    background:#fff;
    border-radius:15px;
    padding:25px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

/* ===== IMAGE ===== */
.card img{
    width:100%;
    /* max-height:220px; */
    object-fit:cover;
    border-radius:10px;
}

/* ===== BUTTON ===== */
.btn{
    display:inline-block;
    margin-top:15px;
    background:#000;
    color:#fff;
    padding:10px 25px;
    border-radius:25px;
    text-decoration:none;
    font-size:.95rem;
}
.btn:hover{opacity:.9;}

/* ===== TEXT ===== */
.card h3{
    margin:15px 0 5px;
}
.card p{
    margin:0;
    color:#666;
    font-size:.9rem;
}
</style>
</head>

<body>


<div class="banner">
    <h1>Homenajes que se encuentran en sala</h1>
</div>


<form method="get">
<div class="selector-wrapper">
  <label for="sede">Selecciona la sede</label>
  <select name="sede" onchange="this.form.submit()">
    <option value="">— Seleccione una sede —</option>
    <?php foreach ($sedes as $s): ?>
      <option value="<?= htmlspecialchars($s) ?>"
        <?= (isset($_GET['sede']) && $_GET['sede'] === $s) ? 'selected' : '' ?>>
        <?= htmlspecialchars($s) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>
    </form>





<?php if (empty($servicios)): ?>
  <p>No hay servicios en este rango.</p>
<?php else: ?>
  <div class="grid">
    <?php foreach ($servicios as $s): ?>
      <div class="card">
        <?php if ($s['perfil_fotoPrincipal']): ?>
          <img src="imagenes/<?= htmlspecialchars($s['perfil_fotoPrincipal']) ?>">
        <?php else: ?>
          <img src="assets/header.webp">
        <?php endif; ?>

        <h3><?= htmlspecialchars($s['nombreFallecido']) ?></h3>
        <p><b>Sede:</b> <?= htmlspecialchars($s['sede']) ?></p>
        <p><b>Sala:</b> <?= htmlspecialchars($s['sala']) ?></p>
        <p><b>Exequias:</b> <?= date('d/m/Y H:i', (int)$s['fechaExequias']) ?></p>

        <a class="btn" href="form.php?id=<?= (int)$s['id'] ?>">
          Enviar mensaje
        </a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

</body>
</html>
