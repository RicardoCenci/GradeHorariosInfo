<!DOCTYPE html>
<html lang="en">
<head>
    <?php include(pathTo('src/view/misc/headers.php'))?>
    <link rel="stylesheet" href="<?=url('/src/view/institucional/index.css')?>">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="<?=url('/src/view/institucional/js/index.js')?>" type='module' defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader&display=swap" rel="stylesheet">
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
            <!-- <div id="horarios">
            <div class='gridItem'>
                    <p title='Ordem'>O</p>
                    <p title='Horario de Entrada' style='padding:5px'>Entrada</p>
                    <p title='Horario de Saida'>Saida</p>
                </div>
            </div> -->
            <div class="actions">
                <h1>Ações</h1>
                <fieldset>
                    <label for="edit">Editar Horario</label>
                    <div id="edit" active></div>
                </fieldset>
                <fieldset>
                    <label for="ordem">Editar ordem dos horarios</label>
                    <div id="ordem"></div>
                </fieldset>

            </div>
            <ul id='horarios' data-mode='edit'>
                <li>
                    <p>ID</p>
                    <p>Entrada</p>
                    <p>Saida</p>
                </li>
            </ul>
        </section>
    </main> 



    <div id="modal">
        <div class="modalContent">
            <div id='wrapper'>
                <h1>Horario</h1>
                <div>
                    <fieldset>
                        <label for="exitTime">ID</label>
                        <input type="text" id='ID' disabled>
                    </fieldset>

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
                <div id="confirmAction">
                    <div class="actionIconContainer">
                        <div class="actionIcon">!</div>
                    </div>
                    <div class="actionConfirmText">
                        <h1>Você tem certeza?</h1>
                        <p class='actionConfirmSub'>Essa ação não podera ser revertida</p>
                        <p>Excluir <span id='deletePreview'>Horario 5: 12:00 ate 13:30</span>?</p>
                    </div>
                    <fieldset class='buttonGroup'> 
                        <div>
                            <button id="ActionConfirmButton" class='button danger'>Excluir</button>
                            <button id="ActionCancelButton" class='button cancel'>Voltar</button>
                        </div>
                    <fieldset>
                </div>
            </div>
    </div>
</html>