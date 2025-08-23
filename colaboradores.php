<?php
require 'includes/app.php';
incluirTemplate('header');
?>

<main class="contenedor seccion colaboradores-seccion">
    <h1 class="text-center mb-4">Conoce al equipo de Servicios Inmobiliarios Aguascalientes</h1>

    <div class="descripcion-colaboradores">
        <p>
            En esta sección encontrarás a los líderes responsables de las diferentes áreas de nuestra agencia, así como al equipo de asesores que forman parte activa de nuestra fuerza de ventas.
        </p>
        <p>
            Si ya eres cliente o estás por convertirte en uno, aquí podrás identificar con quién comunicarte según el tipo de trámite o consulta que tengas. Además, podrás verificar que el asesor que te está atendiendo forma parte oficial de SIA.
        </p>
        <p>
            Estamos aquí para ayudarte con transparencia, profesionalismo y compromiso.
        </p>
    </div>

    <section class="colaboradores-top contenedor">
        <h2 class="titulo-seccion text-center">Líderes del equipo</h2>
        <div class="grid-top">
            <!-- 1. Edmundo -->
            <div class="colaborador-top">
                <img src="build/img/colaboradores/EdmundoTOP.webp" alt="Edmundo - Director General">
                <p class="cargo">Director General</p>
                <p class="descripcion">
                    Líder y fundador de SIA. Responsable de supervisar todas las áreas de la agencia y velar por el cumplimiento de nuestra misión: ayudarte a encontrar tu hogar ideal con profesionalismo y cercanía.
                </p>
            </div>

            <!-- 2. Kike Ibarra -->
            <div class="colaborador-top">
                <img src="build/img/colaboradores/KikeIbarraTOP.webp" alt="Kike Ibarra - Gerente de Ventas">
                <p class="cargo">Gerente de Ventas</p>
                <p class="descripcion">
                    Responsable de coordinar al equipo comercial y garantizar una experiencia de compra eficiente y transparente. Si tienes dudas sobre el proceso de venta o sobre nuestros inmuebles, acércate a él.
                </p>
            </div>

            <!-- 3. Sarahi -->
            <div class="colaborador-top">
                <img src="build/img/colaboradores/SarahiTOP.webp" alt="Sarahi - CFO / Finanzas">
                <p class="cargo">Gerente de Formalización</p>
                <p class="descripcion">
                    Encargada de dar seguimiento puntual a los trámites una vez que se concreta una venta. Si ya estás en proceso de compra, ella es tu punto de contacto para cualquier duda sobre escrituras, créditos o avances.
                </p>
            </div>

            <!-- 4. Zaira -->
            <div class="colaborador-top">
                <img src="build/img/colaboradores/ZairaTOP.webp" alt="Zaira - Gerente de Formalización">
                <p class="cargo">CFE / Finanzas y Calidad</p>
                <p class="descripcion">
                    Además de manejar los temas financieros, supervisa que el servicio al cliente se mantenga con los más altos estándares de calidad. Si tienes comentarios, sugerencias o una situación que requiera atención especial, ella puede ayudarte.
                </p>
            </div>
        </div>
    </section>

    <section class="asesores-seccion">
        <h2 class="titulo-seccion text-center">¿Ya estás siendo atendido por uno de nuestros asesores?</h2>
        <p class="descripcion-asesores text-center">
            Aquí puedes verificar que tu asesor forma parte oficial de nuestro equipo de ventas.<br>
            Si no lo encuentras en la lista, por favor contáctanos directamente para tu tranquilidad.
        </p>

        <div class="asesores-slider">
            <div class="slider-track">
                <?php
                $asesores = [
                    'Adriana.webp',
                    'Alma.webp',
                    'Andrea.webp',
                    'Brandon.webp',
                    'Cesar.webp',
                    'Dhamar.webp',
                    'Edith.webp',
                    'Gabriela.webp',
                    'Giovanni.webp',
                    'Israel.webp',
                    'KikeTorres.webp',
                    'Leonardo.webp',
                    'LuisAdrian.webp',
                    'LuisFernando.webp'
                ];

                foreach ($asesores as $asesor) {
                    echo "<div class='slide'>
                            <img src='/build/img/colaboradores/$asesor' alt='Asesor'>
                          </div>";
                }

                // Repetimos el slider para lograr efecto infinito
                foreach ($asesores as $asesor) {
                    echo "<div class='slide'>
                            <img src='/build/img/colaboradores/$asesor' alt='Asesor'>
                          </div>";
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php
incluirTemplate('footer');
?>