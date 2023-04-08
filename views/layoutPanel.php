<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="icon" href="/build/img/sistema/sacinv-icon.png">
    <title>SACINV</title>
</head>
<body class="body">

    <?php include_once __DIR__ . '/templates/menu.php' ?>
    
    <main class="panel-main">
        <?php echo $contenido; ?>
    </main>
</body>

<script src="/build/js/panel/sweetalert2.js"></script>
<?php echo $script ?? ''; ?>

</html>