<!DOCTYPE html>
<html lang="en">
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

    <?php echo $contenido; ?>

</body>
<?php echo $script ?? ''; ?>
</html>