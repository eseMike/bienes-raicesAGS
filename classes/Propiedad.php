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
            VALUES (:titulo, :precio, :descripcion, :habitaciones, :wc, :estacionamiento, :vendedor_id, :imagen, CURDATE())");

            $query->bindParam(':titulo', $this->titulo);
            $query->bindParam(':precio', $this->precio);
            $query->bindParam(':descripcion', $this->descripcion);
            $query->bindParam(':habitaciones', $this->habitaciones, PDO::PARAM_INT);
            $query->bindParam(':wc', $this->wc, PDO::PARAM_INT);
            $query->bindParam(':estacionamiento', $this->estacionamiento, PDO::PARAM_INT);
            $query->bindParam(':vendedor_id', $this->vendedor_id, PDO::PARAM_INT);
            $query->bindParam(':imagen', $this->imagen);

            $resultado = $query->execute();

            if (!$resultado) {
                echo "⚠️ Error en la inserción en la base de datos: ";
                print_r($query->errorInfo()); // Muestra el error detallado de MySQL
                return false; // Devolvemos false si hay error
            }

            return $resultado;
        } catch (PDOException $e) {
            echo "❌ Error de PDO: " . $e->getMessage();
            return false;
        }
    }

    public function eliminar($id)
    {
        try {
            // Obtener la imagen actual para eliminarla del servidor
            $stmt = $this->db->prepare("SELECT imagen FROM propiedades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($propiedad && !empty($propiedad['imagen'])) {
                $rutaImagen = __DIR__ . "/../../build/img/" . $propiedad['imagen'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen); // Eliminar imagen del servidor
                }
            }

            // Eliminar la propiedad de la base de datos
            $stmt = $this->db->prepare("DELETE FROM propiedades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "❌ Error de PDO al eliminar: " . $e->getMessage();
            return false;
        }
    }
}
