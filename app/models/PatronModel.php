<?php
class PatronModel {
    public $simbolos;
    private $paleta;

    public function __construct() {
        // Paleta vacía, se llenará dinámicamente
        $this->paleta = [];
        $this->simbolos = [];
    }

    private function colorToString($r, $g, $b) {
        return $r . ',' . $g . ',' . $b;
    }

    public function generarPatron($ruta, $ancho=40, $alto=40) {
        $celda = 70; // Cuadros mucho más grandes
        $img = imagecreatefromstring(file_get_contents($ruta));
        $resized = imagecreatetruecolor($ancho, $alto);
        // Si la imagen original tiene transparencia, conservarla al redimensionar
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $ancho, $alto, imagesx($img), imagesy($img));

        $canvas = imagecreatetruecolor($ancho*$celda+200, $alto*$celda);
        $blanco = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $blanco);

        $leyenda = [];
        $negro = imagecolorallocate($canvas, 0, 0, 0);
        $colorIds = [];
        $colorIndex = 1;

        // Primer recorrido: identificar todos los colores únicos (ignorando transparencia)
        for ($y=0; $y<$alto; $y++) {
            for ($x=0; $x<$ancho; $x++) {
                $rgba = imagecolorat($resized, $x, $y);
                $a = ($rgba & 0x7F000000) >> 24;
                $r = ($rgba >> 16) & 0xFF;
                $g = ($rgba >> 8) & 0xFF;
                $b = $rgba & 0xFF;
                // Si el píxel es totalmente transparente, lo tratamos como blanco
                if ($a === 127) {
                    $r = 255; $g = 255; $b = 255;
                }
                $key = $this->colorToString($r, $g, $b);
                if (!isset($colorIds[$key])) {
                    $colorIds[$key] = $colorIndex;
                    $this->paleta[$key] = [$r, $g, $b];
                    $colorIndex++;
                }
            }
        }

        // Segundo recorrido: dibujar y asignar identificador
        for ($y=0; $y<$alto; $y++) {
            for ($x=0; $x<$ancho; $x++) {
                $rgba = imagecolorat($resized, $x, $y);
                $a = ($rgba & 0x7F000000) >> 24;
                $r = ($rgba >> 16) & 0xFF;
                $g = ($rgba >> 8) & 0xFF;
                $b = $rgba & 0xFF;
                if ($a === 127) {
                    $r = 255; $g = 255; $b = 255;
                }
                $key = $this->colorToString($r, $g, $b);
                $id = $colorIds[$key];
                $color = imagecolorallocate($canvas, $r, $g, $b);
                imagefilledrectangle($canvas, $x*$celda, $y*$celda, ($x+1)*$celda, ($y+1)*$celda, $color);
                // Bordes más gruesos
                for ($grosor = 0; $grosor < 3; $grosor++) {
                    imagerectangle(
                        $canvas,
                        $x*$celda + $grosor,
                        $y*$celda + $grosor,
                        ($x+1)*$celda - $grosor,
                        ($y+1)*$celda - $grosor,
                        $negro
                    );
                }
                $leyenda[$key] = [$r, $g, $b, $id];
            }
        }

        // Generar lista de símbolos/identificadores para la leyenda
        $this->simbolos = [];
        foreach ($leyenda as $key => $info) {
            $this->simbolos[$key] = $info[3];
        }

        return [
            "canvas" => $canvas,
            "leyenda" => $leyenda
        ];
    }

    /**
     * Recorta una imagen dada según las coordenadas y dimensiones.
     * @param string $ruta Ruta de la imagen original
     * @param int $x Coordenada X de inicio
     * @param int $y Coordenada Y de inicio
     * @param int $w Ancho del recorte
     * @param int $h Alto del recorte
     * @return GdImage Imagen recortada
     */
    public function recortarImagen($ruta, $x, $y, $w, $h) {
        // Permitir rutas relativas desde public/uploads o absolutas
        if (!file_exists($ruta)) {
            $rutaRelativa = __DIR__ . '/../../' . ltrim($ruta, '/');
            if (file_exists($rutaRelativa)) {
                $ruta = $rutaRelativa;
            } else {
                die('No se encontró la imagen para recortar: ' . htmlspecialchars($ruta));
            }
        }
        $imgData = file_get_contents($ruta);
        if ($imgData === false) {
            die('No se pudo leer la imagen para recortar.');
        }
        $img = imagecreatefromstring($imgData);
        if ($img === false) {
            die('El archivo no es una imagen válida.');
        }
        $recortada = imagecreatetruecolor($w, $h);
        imagecopy($recortada, $img, 0, 0, $x, $y, $w, $h);
        return $recortada;
    }

    /**
     * Descarga una imagen PNG generada en memoria.
     * @param GdImage $img Recurso de imagen
     * @param string $nombreArchivo Nombre sugerido para la descarga
     */
    public function descargarImagen($img, $nombreArchivo = 'recorte.png') {
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        imagepng($img);
        imagedestroy($img);
        exit;
    }
}
