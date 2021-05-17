
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <?php include(pathTo('src/view/misc/headers.php'))?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?=url('/src/view/home/index.css')?>">
    <script type="module" src="<?=url('src/view/home/js/index.js')?>" defer></script>
    <script type="module" src="<?=url('src/view/home/js/DragAndDrop.js')?>" defer></script>
    <style>
        .button-add::after{
            background-image: url("<?=url('/resources/plus_icon.svg')?>");
        }
        #deleteCard{
            background-image: url("<?=url('/resources/delete.svg')?>");
        }
    </style>
</head>
<body>
    <!-- <div class="card" style="--cardBackground:red">
        <div class="card-color"></div>
        <div class="card-info">
            <div class="card-name">Placeholder name</div>
            <div class="card-materia">Placeholder</div>
        </div>
    </div> -->
    <div class="content-container">
        <div class="nav-container">
            <div class="menu-icon-container">
                <div title="Menu" id="menu-icon" ></div>
            </div>
            <nav id="navMenu">
                <ul>
                    <li>
                        <p>Turmas</p>
                        <div class='menu-options'>
                        <?php foreach ($cursos as $cursoId => $curso): ?>

                            <p class='submenu-title'><?=$curso['nome']?></p>
                            <div class="submenu-option">

                            <?php foreach ($curso['turmas'] as $turma): ?>

                                    <div class='menu-item' data-cursoId='<?=$turma['cursoId']?>' data-turmaId='<?=$turma['turmaId']?>'><?=$turma['turma']?></div>

                        <?php endforeach?>
                        </div>
                        <?php endforeach?>
    
                    </li>
                    <li>
                        <p class='menu-option-title' data-url='./institucional'>Insitucional</p>
                        <div class='menu-options'>
                            <p class='submenu-title menu-option-title' data-url='./institucional'>Ministrar Professores</p>
                            <p class='submenu-title menu-option-title' data-url='./institucional'>Ministrar Cursos</p>
                    </li>
                    <li>
                        <p class='menu-option-title' data-url='./configuracoes'>Configurações</p>
                    </li>
                    <li>
                        <p class='menu-option-title' data-url='./logout'>Sair</p>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="sub-container">
                    
            <div class="grade-container" id='grade'>
                <div id="loadingGrade" active>
                    <div class="loadingIcon"></div>
                </div>
                <div class="grade-row dias">
                    <div class="grade-col horarios"></div>
                    <div class="grade-col">Segunda</div>
                    <div class="grade-col">Terca</div>
                    <div class="grade-col">Quarta</div>
                    <div class="grade-col">Quinta</div>
                    <div class="grade-col">Sexta</div>
                </div>
            </div>

        </div>
        <div data-id="list" id="list" class="cards-container">
            <div class="cards-header">
                <div class="cards-title">Docentes</div>
                <div class="button-bar">
                    <div title="Adicionar docente à grade" class="button-add" id='addMenuIcon'></div>
                </div>
            </div>
            <div id='addCard'>
                    <label for="professorInput">Professor</label>
                    <input id='professorInput' list='professores'>

                    <label for="materiasInput">Materia Ministrada</label>
                    <input id='materiasInput' placeholder='Selecione um professor' disabled list='materias'>
                    
                    <div>
                        <label for="professorFichas">Numero de fichas</label>
                        <input type="number" id="professorFichas" min="1" value='1'>
                    </div>
                    <div>
                        <button id='addProfessor'>Adicionar</button>
                        <button id='cancelProfessor'>Cancelar</button>
                    </div>
            </div>
            <div id='deleteCard'></div>
        </div>
    </div>
    <datalist id="professores">

    </datalist>
    <datalist id="materias"></datalist>
</body>
</html>