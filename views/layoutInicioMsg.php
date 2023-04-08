<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="icon" href="/build/img/sistema/sacinv-icon.png">
    <title>SACINV</title>
</head>
<body>
    <?php 
        include_once __DIR__ . '/templates/notificacion.php';
    ?>

    <div class="contenedor-olvide">

        <header class="header-olvide contenedor">
            <div class="titulo">
                <a href="/">
                    <img src="/build/img/sistema/logo-blanco.svg" alt="Logo">
                </a>
            </div>
        </header>

        <main class="contenedor">        
            <?php echo $contenido; ?>
        </main>

    </div>

</body>
<?php echo $script ?? ''; ?>
</html>
