<?php
require_once 'funciones_pdf.php';

date_default_timezone_set('America/Bogota');

$ID_SQ = intval($_GET['ID_SQ'] ?? 0);

if ($ID_SQ) {
    generar_pdf_condolencias($ID_SQ, 'I');
} else {
    echo "ID inválido";
}
