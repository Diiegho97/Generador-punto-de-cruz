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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23e83e8c' d='M256 32c-17.7 0-32 14.3-32 32v64.1c-13.2-7.6-28.5-12.1-45-12.1-53 0-96 43-96 96 0 16.5 4.5 31.8 12.1 45H64c-17.7 0-32 14.3-32 32s14.3 32 32 32h64.1c-7.6 13.2-12.1 28.5-12.1 45 0 53 43 96 96 96 16.5 0 31.8-4.5 45-12.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32v-64.1c13.2 7.6 28.5 12.1 45 12.1 53 0 96-43 96-96 0-16.5-4.5-31.8-12.1-45H448c17.7 0 32-14.3 32-32s-14.3-32-32-32h-64.1c7.6-13.2 12.1-28.5 12.1-45 0-53-43-96-96-96-16.5 0-31.8 4.5-45 12.1V64c0-17.7-14.3-32-32-32z'/%3E%3C/svg%3E">
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
