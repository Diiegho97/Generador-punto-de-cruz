<?php
$uploadDir = __DIR__ . "/../../public/uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
// Obtener el nombre original de la imagen subida
global $nombreOriginal;
if (!isset($nombreOriginal) || empty($nombreOriginal)) {
    $nombreOriginal = 'patron.png';
}
$tempFile = $uploadDir . basename($nombreOriginal);
imagepng($canvas, $tempFile);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Patrón Generado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Story+Script&display=swap" rel="stylesheet">
</head>
<body style="background: url('uploads/telallo_paris.jpg') center center / cover no-repeat fixed; image-rendering: auto; background-attachment: fixed; background-size: cover; background-position: center center; background-repeat: no-repeat;" class="bg-light">
<div class="container py-5">
    <div class="card shadow rounded-3">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center">Patrón de imagen generado</h3>
            <style>
                h2 {
                font-family: "Story Script", sans-serif;
                font-weight: 400;
                font-style: normal;
                }
            </style>
            <div class="text-center mb-4">
                <img src="uploads/<?= htmlspecialchars(basename($nombreOriginal)) ?>" class="img-fluid border" alt="Patrón de punto de cruz">
            </div>
            <div class="text-center mb-4">
                <a href="?c=Patron" class="btn btn-danger me-2 px-4">Volver</a>
                <a href="?c=Patron&a=recortar&img=public/uploads/<?= urlencode(basename($nombreOriginal)) ?>&nombreOriginal=<?= urlencode(basename($nombreOriginal)) ?>" class="btn btn-warning px-4">Recortar y Descargar</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
