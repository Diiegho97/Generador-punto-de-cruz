<?php
class PatronController {

    public function index() {
        // Mostrar formulario
        require __DIR__ . '/../views/formulario.php';
    }

    public function generar() {
        if (!isset($_FILES['imagen'])) {
            die("No se subió ninguna imagen.");
        }

        // Validación previa de dimensiones y peso de imagen ANTES de cargarla en memoria
        $maxWidth = 4000; // Máximo permitido por seguridad (ajusta según tu servidor)
        $maxHeight = 4000;
        $maxFileSize = 8 * 1024 * 1024; // 8MB

        // Validar tamaño de archivo antes de moverlo
        if ($_FILES['imagen']['size'] > $maxFileSize) {
            $this->mostrarErrorSweetAlert('El tamaño maximo es de 8MB para este servidor. Redimencione su imagen');
            return;
        }

        // Validar que sea una imagen válida y obtener dimensiones SIN cargarla en memoria
        $info = getimagesize($_FILES['imagen']['tmp_name']);
        if ($info === false) {
            $this->mostrarErrorSweetAlert('Error: el archivo subido no es una imagen válida.');
            return;
        }
        $imgWidth = $info[0];
        $imgHeight = $info[1];
        if ($imgWidth > $maxWidth || $imgHeight > $maxHeight) {
            $this->mostrarErrorSweetAlert('La imagen es demasiado grande (máx ' . $maxWidth . 'x' . $maxHeight . 'px). Puedes reducirla en <a href="https://www.iloveimg.com/es/redimensionar-imagen" target="_blank">iloveimg.com/redimensionar-imagen</a>.');
            return;
        }

        require __DIR__ . '/../models/PatronModel.php';
        $patronModel = new PatronModel();

        $nombreOriginal = $_FILES['imagen']['name'];
        $rutaDestino = __DIR__ . '/../../public/uploads/' . $nombreOriginal;
        // Validar subida y existencia del archivo
        if (!is_uploaded_file($_FILES['imagen']['tmp_name']) || !move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $this->mostrarErrorSweetAlert('Error: la imagen no se subió correctamente. Intenta de nuevo.');
            return;
        }
        if (!file_exists($rutaDestino)) {
            $this->mostrarErrorSweetAlert('Error: la imagen no se guardó en el servidor.');
            return;
        }

        $ancho = isset($_POST['ancho']) ? (int)$_POST['ancho'] : 100;
        $alto = isset($_POST['alto']) ? (int)$_POST['alto'] : 100;
        if ($ancho > 2000 || $alto > 2000) {
            $this->mostrarErrorSweetAlert('El tamaño maximo es de 8MB para este servidor. Redimencione su imagen');
            return;
        }

        // Validación preventiva de memoria antes de procesar
        $maxMemoria = 128 * 1024 * 1024; // 128MB (ajusta según tu servidor)
        $memoriaNecesaria = $ancho * $alto * 4; // 4 bytes por píxel (RGBA)
        if ($memoriaNecesaria > $maxMemoria * 0.7) { // deja margen de seguridad
            $this->mostrarErrorSweetAlert('El tamaño maximo es de 8MB para este servidor. Redimencione su imagen');
            return;
        }

        // Intentar procesar la imagen y capturar errores de memoria
        try {
            $resultado = $patronModel->generarPatron($rutaDestino, $ancho, $alto);
        } catch (Throwable $e) {
            if (strpos($e->getMessage(), 'Allowed memory size') !== false) {
                $this->mostrarErrorSweetAlert('El tamaño maximo es de 8MB para este servidor. Redimencione su imagen');
                return;
            } else {
                $this->mostrarErrorSweetAlert('Ocurrió un error inesperado: ' . $e->getMessage());
                return;
            }
        }

        // Pasar el resultado a la vista
        $leyenda = $resultado['leyenda'];
        $canvas = $resultado['canvas'];
        $simbolos = $patronModel->simbolos; // <-- Pasar símbolos a la vista
        require __DIR__ . '/../views/patron.php';
    }

    private function mostrarErrorSweetAlert($mensaje) {
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body class="bg-light">';
        echo '<script>Swal.fire({icon: "error", title: "¡Error!", text: "' . addslashes($mensaje) . '", confirmButtonText: "Cerrar"}).then(() => { window.history.back(); });</script>';
        echo '</body></html>';
    }

    public function recortar() {
        // Mostrar formulario de recorte
        $imgPath = isset($_GET['img']) ? $_GET['img'] : '';
        $imgPreview = $imgPath ? 'uploads/' . basename($imgPath) : '';
        // Usar el nombre original con extensión si viene por GET, si no, usar el nombre base
        $nombreOriginal = isset($_GET['nombreOriginal']) ? $_GET['nombreOriginal'] : ($imgPath ? basename($imgPath) : 'imagen');
        require __DIR__ . '/../views/recortar.php';
    }

    public function recortarDescargar() {
        if (!isset($_POST['ruta'], $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'])) {
            die('Datos de recorte incompletos.');
        }
        require __DIR__ . '/../models/PatronModel.php';
        $patronModel = new PatronModel();
        $imgPath = $_POST['ruta'];
        $x = (int)$_POST['x'];
        $y = (int)$_POST['y'];
        $w = (int)$_POST['w'];
        $h = (int)$_POST['h'];
        $nombreOriginal = isset($_POST['nombreOriginal']) ? $_POST['nombreOriginal'] : 'imagen';
        // Quitar extensión si existe
        $nombreBase = pathinfo($nombreOriginal, PATHINFO_FILENAME);
        $recorte = $patronModel->recortarImagen($imgPath, $x, $y, $w, $h);
        $patronModel->descargarImagen($recorte, $nombreBase . '_puntodecruz.png');
    }
}
