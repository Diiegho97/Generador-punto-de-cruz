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

        // Solo validar que sea una imagen válida
        $info = getimagesize($_FILES['imagen']['tmp_name']);
        if ($info === false) {
            $this->mostrarErrorSweetAlert('Error: el archivo subido no es una imagen válida.');
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
