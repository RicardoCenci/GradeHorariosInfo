<!DOCTYPE html>
<html lang="en">
<head>
    <?php include(pathTo('src/view/misc/headers.php'))?>
    <link rel="stylesheet" href="<?=url('/src/view/institucional/index.css')?>">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="<?=url('/src/view/institucional/js/index.js')?>" type='module' defer></script>
    <title>Document</title>
</head>
<body>
    <nav id="navbar">
        <img src="<?=url('/resources/instituition_default_image.jpg')?>" alt="" srcset="">
        <ul>
            <li active>Horarios</li>
            <li>Cursos</li>
            <li>Professores</li>
            <li>Materias</li>
        </ul>
    </nav>
    <main id='root'>
        <h1 id='appTittle'>Horarios</h1>
        <section id='app'>
       

        </section>
    </main>
</html>