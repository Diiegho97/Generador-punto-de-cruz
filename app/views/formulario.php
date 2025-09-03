<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generador Patrón Punto de Cruz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Story+Script&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23e83e8c' d='M256 32c-17.7 0-32 14.3-32 32v64.1c-13.2-7.6-28.5-12.1-45-12.1-53 0-96 43-96 96 0 16.5 4.5 31.8 12.1 45H64c-17.7 0-32 14.3-32 32s14.3 32 32 32h64.1c-7.6 13.2-12.1 28.5-12.1 45 0 53 43 96 96 96 16.5 0 31.8-4.5 45-12.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32v-64.1c13.2 7.6 28.5 12.1 45 12.1 53 0 96-43 96-96 0-16.5-4.5-31.8-12.1-45H448c17.7 0 32-14.3 32-32s-14.3-32-32-32h-64.1c7.6-13.2 12.1-28.5 12.1-45 0-53-43-96-96-96-16.5 0-31.8 4.5-45 12.1V64c0-17.7-14.3-32-32-32z'/%3E%3C/svg%3E">
</head>
<body style="background: url('uploads/telallo_paris.jpg') center center / cover no-repeat fixed; image-rendering: auto; background-attachment: fixed; background-size: cover; background-position: center center; background-repeat: no-repeat;" class="bg-light">
<div class="container py-4 px-2 px-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow rounded-3">
                <div class="card-body p-3 p-md-4">
                    <h1 class="card-title mb-4 text-center">Convertidor a Patrón de Punto de Cruz</h3>
                    <style>
                        h1 {
                        font-family: "Story Script", sans-serif;
                        font-weight: 400;
                        font-style: normal;
                        }
                    </style>
                    <form method="POST" enctype="multipart/form-data" action="?c=Patron&a=generar" id="formPatron">
                        <!-- Imagen -->
                        <div class="mb-3">
                            <label class="form-label">Selecciona una imagen</label>
                            <input type="file" name="imagen" id="imagenInput" class="form-control" accept="image/*" required>
                        </div>

                        <!-- Vista previa -->
                        <div class="mb-3 text-center">
                            <img id="preview" src="" alt="Vista previa" class="img-fluid d-none border rounded" style="max-height:200px; max-width:100%;"/>
                        </div>

                        <!-- Ancho -->
                        <div class="mb-3">
                            <div class="row g-2 align-items-end">
                                <div class="col-6">
                                    <label class="form-label">Ancho de la cuadrícula (puntos)</label>
                                    <input type="number" name="ancho" id="ancho" class="form-control" value="100" min="10" max="2000" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Alto de la cuadrícula (puntos)</label>
                                    <input type="number" name="alto" id="alto" class="form-control" value="100" min="10" max="2000" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Generar Patrón</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Validación antes de enviar
    $("#formPatron").on("submit", function(e){
        let ancho = $("#ancho").val();
        let alto = $("#alto").val();
        if(ancho < 10 || alto < 10){
            alert("El ancho y alto deben ser al menos 10.");
            e.preventDefault();
        }
        if(ancho > 2000 || alto > 2000){
            alert("El ancho y alto no pueden ser mayores a 2000 por limitación del servidor.");
            e.preventDefault();
        }
    });

    // Vista previa de la imagen seleccionada
    $("#imagenInput").on("change", function(){
        const file = this.files[0];
        if(file){
            let reader = new FileReader();
            reader.onload = function(e){
                $("#preview").attr("src", e.target.result).removeClass("d-none");
            }
            reader.readAsDataURL(file);
        } else {
            $("#preview").attr("src", "").addClass("d-none");
        }
    });
});
</script>
</body>
</html>
