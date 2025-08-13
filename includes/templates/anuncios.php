<?php
// Importar la conexión

$db = conectarDB();

// Consultar
$query = "SELECT * FROM propiedades LIMIT " . $limite;


// Preparar la consulta
$stmt = $db->prepare($query);
$stmt->execute();

// Obtener resultados
?>

<div data-aos="fade-up" class="contenedor-anuncios">
    <?php while ($propiedad = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <!-- anuncios -->
        <div class="anuncio">

            <picture>
                <?php $ruta = "/build/img/" . basename($propiedad['imagen']); ?>
                <source srcset="<?php echo $ruta; ?>" type="image/webp">
                <source srcset="<?php echo $ruta; ?>" type="image/jpeg">
                <img loading="lazy" src="<?php echo $ruta; ?>" alt="anuncio">
            </picture>


            <div class="contenido-anuncio">
                <h3><?php echo $propiedad['titulo']; ?></h3>
                <p>
                    <?php echo $propiedad['descripcion']; ?>
                </p>
                <p class="precio">$<?php echo $propiedad['precio']; ?></p>
                <ul class="iconos-caracteristicas">
                    <li>
                        <i class="fa-solid fa-toilet"></i>
                        <p><?php echo $propiedad['wc']; ?></p>
                    </li>
                    <li>
                        <i class="fa-solid fa-car"></i>
                        <p><?php echo $propiedad['estacionamiento']; ?></p>
                    </li>
                    <li>
                        <i class="fa-solid fa-bed"></i>
                        <p><?php echo $propiedad['habitaciones']; ?></p>
                    </li>
                </ul>

                <a class="boton boton-blue" href="anuncio.php?id=<?php echo $propiedad['id']; ?>">Ver propiedad</a>
            </div>
        </div>
    <?php } // Cierra el ciclo while 
    ?>
</div> <!-- Cierra el div contenedor-anuncios -->

<?php
// Fin del código PHP
$db = null;
?>