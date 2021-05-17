<?php
header("HTTP/1.0 405 Method Not Allowed");
?>
<!DOCTYPE html>
<html lang='pt-br'>
    <head>
        <title><?=$data['title']?></title>
        <meta charset='UTF-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel='stylesheet' href='./View/Resources/home/style.css'>
    </head>
        <body class="container-fluid">
            Oops... O metodo utilizado não é autorizado, por favor contate um administrador
        </body>
    <script src='.js'></script>
</html>