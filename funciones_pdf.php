<?php
require_once 'fpdf/fpdf.php';
require_once 'Connections/db.php';

function generar_pdf_condolencias($ID_SQ, $outputMode = 'S') {
    global $pdo;

    if (!$ID_SQ) {
        return null;
    }

    /* ===== CONSULTAR MENSAJES ===== */
    $sql = "
        SELECT nombre, mensaje, createdAt
        FROM mensajesCondolencia
        WHERE obituarioProfile  = :id
        ORDER BY createdAt ASC
    ";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $ID_SQ]);
        $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return null;
    }

    /* ===== PDF ===== */
    $pdf = new FPDF('P','mm','A4');
    /* ===== PORTADA ===== */
    $pdf->AddPage();

    // Imagen superior
    $headerPath = __DIR__ . '/assets/header.jpeg';

    if (file_exists($headerPath)) {
        $pdf->Image($headerPath, 10, 10, 190);
    }
    
    $pdf->Ln(95);

    // Frase principal
    $pdf->SetFont('Arial','',16);
    $pdf->Ln(15);

    /* ===== NUEVA PÁGINA PARA MENSAJES ===== */
    $pdf->SetFont('Arial','',12);

    $hasMessages = false;

    if ($mensajes) {
        foreach ($mensajes as $m) {
            $hasMessages = true;

            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
            }

            // Mensaje
            $pdf->SetFont('Times','',13);
            $pdf->MultiCell(
                0,
                8,
                utf8_decode($m['mensaje']),
                0,
                'C'
            );

            $pdf->Ln(6);

            // Autor
            $pdf->SetFont('Arial','I',10);
            $pdf->SetTextColor(120,120,120);
            $pdf->Cell(
                0,
                6,
                utf8_decode(''.$m['nombre']),
                0,
                1,
                'C'
            );

            $pdf->Ln(18);

            $pdf->SetTextColor(0,0,0);
        }
    }

    if (!$hasMessages) {
         // Opcional: Agregar mensaje si no hay condolencias, o simplemente devolver el PDF con la portada
    }

    return $pdf->Output($outputMode, 'Libro_de_Firmas.pdf');
}
