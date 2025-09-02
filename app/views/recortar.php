<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recortar Imagen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Story+Script&display=swap" rel="stylesheet">
    <style>
        #preview {
            max-width: 100%;
            border: 1px solid #888;
            max-height: 500px;
        }
    </style>
</head>
<body style="background: url('uploads/telallo_paris.jpg') center center / cover no-repeat fixed; image-rendering: auto; background-attachment: fixed; background-size: cover; background-position: center center; background-repeat: no-repeat;" class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-3">
                <div class="card-body">
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
                            <img id="preview" src="<?= htmlspecialchars($imgPreview ?? '') ?>" alt="Vista previa">
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
