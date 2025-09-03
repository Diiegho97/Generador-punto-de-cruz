<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recortar Imagen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Story+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23e83e8c' d='M256 32c-17.7 0-32 14.3-32 32v64.1c-13.2-7.6-28.5-12.1-45-12.1-53 0-96 43-96 96 0 16.5 4.5 31.8 12.1 45H64c-17.7 0-32 14.3-32 32s14.3 32 32 32h64.1c-7.6 13.2-12.1 28.5-12.1 45 0 53 43 96 96 96 16.5 0 31.8-4.5 45-12.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32v-64.1c13.2 7.6 28.5 12.1 45 12.1 53 0 96-43 96-96 0-16.5-4.5-31.8-12.1-45H448c17.7 0 32-14.3 32-32s-14.3-32-32-32h-64.1c7.6-13.2 12.1-28.5 12.1-45 0-53-43-96-96-96-16.5 0-31.8 4.5-45 12.1V64c0-17.7-14.3-32-32-32z'/%3E%3C/svg%3E">
    <style>
        #preview {
            max-width: 100%;
            border: 1px solid #888;
            max-height: 500px;
        }
    </style>
</head>
<body style="background: url('../public/img/telallo_paris.jpg') center center / cover no-repeat fixed; image-rendering: auto; background-attachment: fixed; background-size: cover; background-position: center center; background-repeat: no-repeat;" class="bg-light">
<div class="container py-4 px-2 px-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow rounded-3" style="background: rgba(255,255,255,0.92);">
                <div class="card-body p-3 p-md-4">
                    <h2 class="card-title mb-4 text-center">Recortar</h3>
                    <style>
                        h2 {
                        font-family: "Story Script", sans-serif;
                        font-weight: 400;
                        font-style: normal;
                        }
                    </style>
                    <form method="POST" action="?c=Patron&a=recortarDescargar" id="formRecorte" enctype="multipart/form-data">
                        <input type="hidden" name="x" id="cropX">
                        <input type="hidden" name="y" id="cropY">
                        <input type="hidden" name="w" id="cropW">
                        <input type="hidden" name="h" id="cropH">
                        <input type="hidden" name="ruta" value="<?= htmlspecialchars($imgPath ?? '') ?>">
                        <input type="hidden" name="nombreOriginal" value="<?= htmlspecialchars($nombreOriginal ?? 'imagen') ?>">
                        <div class="text-center mb-3">
                            <img id="preview" src="<?= htmlspecialchars($imgPreview ?? '') ?>" alt="Vista previa" class="img-fluid border rounded" style="max-width:100%;max-height:300px;" >
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="?c=Patron" class="btn btn-danger me-2 flex-fill">Volver</a>
                            <button type="submit" class="btn btn-primary flex-fill ms-2">Descargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper;
document.addEventListener('DOMContentLoaded', function() {
    const image = document.getElementById('preview');
    if (image && image.src) {
        cropper = new Cropper(image, {
            viewMode: 1,
            autoCropArea: 1,
            movable: true,
            zoomable: true,
            scalable: true,
            crop(event) {
                document.getElementById('cropX').value = Math.round(event.detail.x);
                document.getElementById('cropY').value = Math.round(event.detail.y);
                document.getElementById('cropW').value = Math.round(event.detail.width);
                document.getElementById('cropH').value = Math.round(event.detail.height);
            }
        });
    }
    document.getElementById('formRecorte').addEventListener('submit', function(e) {
        if (cropper) {
            const data = cropper.getData(true);
            document.getElementById('cropX').value = Math.round(data.x);
            document.getElementById('cropY').value = Math.round(data.y);
            document.getElementById('cropW').value = Math.round(data.width);
            document.getElementById('cropH').value = Math.round(data.height);
        }
        // Mostrar alerta de éxito tras un pequeño delay para permitir la descarga
        setTimeout(function() {
            Swal.fire({
                icon: 'success',
                title: '¡Descarga exitosa!',
                text: 'La imagen se ha descargado correctamente.',
                text: 'Será dirigid@ de vuelta al formulario principal.',
                confirmButtonText: 'Cerrar',
                showCancelButton: false,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?c=Patron';
                }
            });
        }, 1000);
    });
});
</script>
</body>
</html>
