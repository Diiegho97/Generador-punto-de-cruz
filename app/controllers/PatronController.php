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

        require __DIR__ . '/../models/PatronModel.php';
        $patronModel = new PatronModel();

        $nombreOriginal = $_FILES['imagen']['name'];
        $rutaDestino = __DIR__ . '/../../public/uploads/' . $nombreOriginal;
        // Validar subida y existencia del archivo
        if (!is_uploaded_file($_FILES['imagen']['tmp_name']) || !move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            die("Error: la imagen no se subió correctamente. Intenta de nuevo.");
        }
        if (!file_exists($rutaDestino)) {
            die("Error: la imagen no se guardó en el servidor.");
        }
        // Validar que sea una imagen válida
        $info = getimagesize($rutaDestino);
        if ($info === false) {
            unlink($rutaDestino);
            die("Error: el archivo subido no es una imagen válida.");
        }

        $ancho = isset($_POST['ancho']) ? (int)$_POST['ancho'] : 100;
        $alto = isset($_POST['alto']) ? (int)$_POST['alto'] : 100;
        $resultado = $patronModel->generarPatron($rutaDestino, $ancho, $alto);

        // Pasar el resultado a la vista
        $leyenda = $resultado['leyenda'];
        $canvas = $resultado['canvas'];
        $simbolos = $patronModel->simbolos; // <-- Pasar símbolos a la vista
        require __DIR__ . '/../views/patron.php';
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
