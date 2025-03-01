<?php
$image = imagecreatefromjpeg('ruta/a/una/imagen.jpg'); // Cambia la ruta a una imagen válida
if ($image) {
    $image = imagecreatefromjpeg('/Users/mikealbarran/Desktop/bienes-raicesAGS2025/build/img/anuncio1.jpg');
    imagedestroy($image);
    echo "Conversión exitosa";
} else {
    echo "Error al abrir la imagen";
}
