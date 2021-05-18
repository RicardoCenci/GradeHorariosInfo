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
            <div id="horarios">
            <div class='gridItem'>
                    <p>ID</p>
                    <p>Entrada</p>
                    <p>Saida</p>
                </div>
                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>
                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>                <div class='gridItem'>
                    <p>1</p>
                    <p>12:30</p>
                    <p>12:30</p>
                </div>


            </div>
        </section>
    </main> 
    <div id="modal">
        <div class="modalContent">
            <h1>Horario</h1>
            <div>
                <fieldset>
                    <label for="entranceTime">Entrada</label>
                    <input type="text" id='entranceTime'>
                </fieldset>
                <fieldset>
                    <label for="exitTime">Saida</label>
                    <input type="text" id='exitTime'>
                </fieldset>
            </div>

            <fieldset class='buttonGroup'> 
                <div>
                    <button id='save' class='button accept'>Salvar</button>
                    <button id='cancel' class='button cancel'>Cancelar</button>
                </div>
                <button id='delete' class='button danger'>Excluir</button>
            </fieldset>
        </div>
    </div>
</html>