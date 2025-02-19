<?php

namespace App;

use PDO;
use PDOException;

class Propiedad
{
    private $db;
    public $titulo;
    public $precio;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $vendedor_id;
    public $imagen;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function crear()
    {
        try {
            $query = $this->db->prepare("INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, vendedores_id, imagen, creado) 
            VALUES (:titulo, :precio, :descripcion, :habitaciones, :wc, :estacionamiento, :vendedor_id, :imagen, NOW())");

            $query->bindParam(':titulo', $this->titulo);
            $query->bindParam(':precio', $this->precio);
            $query->bindParam(':descripcion', $this->descripcion);
            $query->bindParam(':habitaciones', $this->habitaciones, PDO::PARAM_INT);
            $query->bindParam(':wc', $this->wc, PDO::PARAM_INT);
            $query->bindParam(':estacionamiento', $this->estacionamiento, PDO::PARAM_INT);
            $query->bindParam(':vendedor_id', $this->vendedor_id, PDO::PARAM_INT);
            $query->bindParam(':imagen', $this->imagen);

            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function actualizar($id)
    {
        try {
            $query = $this->db->prepare("UPDATE propiedades SET 
            titulo = :titulo, 
            precio = :precio, 
            descripcion = :descripcion, 
            habitaciones = :habitaciones, 
            wc = :wc, 
            estacionamiento = :estacionamiento, 
            vendedores_id = :vendedor_id, 
            imagen = :imagen 
            WHERE id = :id");

            $query->bindParam(':titulo', $this->titulo);
            $query->bindParam(':precio', $this->precio);
            $query->bindParam(':descripcion', $this->descripcion);
            $query->bindParam(':habitaciones', $this->habitaciones, PDO::PARAM_INT);
            $query->bindParam(':wc', $this->wc, PDO::PARAM_INT);
            $query->bindParam(':estacionamiento', $this->estacionamiento, PDO::PARAM_INT);
            $query->bindParam(':vendedor_id', $this->vendedor_id, PDO::PARAM_INT);
            $query->bindParam(':imagen', $this->imagen);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }


    public function validarImagen($imagen)
    {
        $errores = [];
        if ($imagen && $imagen['tmp_name']) {
            $tamanoMaximo = 2 * 1024 * 1024;
            if ($imagen['size'] > $tamanoMaximo) {
                $errores[] = "La imagen es muy pesada. Debe ser menor a 2 MB.";
            } else {
                $formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
                $tipoImagen = mime_content_type($imagen['tmp_name']);
                if (!in_array($tipoImagen, $formatosPermitidos)) {
                    $errores[] = "El formato de la imagen no es válido.";
                }
            }
        } else {
            $errores[] = "La imagen es obligatoria.";
        }
        return $errores;
    }

    public function eliminar($id)
    {
        try {
            // Obtener la imagen actual para eliminarla
            $stmt = $this->db->prepare("SELECT imagen FROM propiedades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($propiedad && !empty($propiedad['imagen'])) {
                $rutaImagen = "../../imagenes/" . $propiedad['imagen'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }

            // Eliminar la propiedad
            $stmt = $this->db->prepare("DELETE FROM propiedades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
