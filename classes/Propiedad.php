<?php

namespace App;

use PDO;
use PDOException;

class Propiedad
{
    private static PDO $db; // <- conexión estática para uso en métodos estáticos
    private PDO $conexion;  // <- conexión individual por instancia

    public $id;
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
        $this->conexion = $db;
    }

    // Establecer conexión estática
    public static function setDB(PDO $database)
    {
        self::$db = $database;
    }

    public function crear()
    {
        try {
            $query = $this->conexion->prepare("INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, vendedores_id, imagen, creado) 
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
                print_r($query->errorInfo());
                return false;
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
            $stmt = $this->conexion->prepare("SELECT imagen FROM propiedades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($propiedad && !empty($propiedad['imagen'])) {
                $rutaImagen = __DIR__ . "/../../build/img/" . $propiedad['imagen'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }

            $stmt = $this->conexion->prepare("DELETE FROM propiedades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "❌ Error de PDO al eliminar: " . $e->getMessage();
            return false;
        }
    }

    public function actualizar($id)
    {
        try {
            $query = $this->conexion->prepare("UPDATE propiedades SET 
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
            echo "❌ Error de PDO al actualizar: " . $e->getMessage();
            return false;
        }
    }

    // 🔥 Método para obtener propiedades con límite
    public static function allLimit($limite)
    {
        try {
            $query = self::$db->prepare("SELECT * FROM propiedades LIMIT :limite");
            $query->bindParam(':limite', $limite, PDO::PARAM_INT);
            $query->execute();

            $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

            $propiedades = [];
            foreach ($resultados as $registro) {
                $propiedad = new self(self::$db);
                foreach ($registro as $key => $value) {
                    if (property_exists($propiedad, $key)) {
                        $propiedad->$key = $value;
                    }
                }
                $propiedades[] = $propiedad;
            }

            return $propiedades;
        } catch (PDOException $e) {
            echo "❌ Error al obtener propiedades: " . $e->getMessage();
            return [];
        }
    }
}
