<?php
    require 'utility.php';
    require pathTo('src/router/Router.php');

    $router = new Router();
    
    //Public Routes
    // $router->get('/',[WebController::class,'home']);
    $router->uses($router);
    $router->get('/login',[WebController::class,'teste']);


    //Protected Routes under the Authenticator/auth funcion
    $router->uses(Authenticator::class, 'auth');
    $router->get('/',[WebController::class,'home']);
    $router->post('/',[WebController::class,'home']);
    $router->get('/institucional',[WebController::class,'institucional']);


    $router->uses(Authenticator::class, 'APIAuth');
    $router->get('/professor/:data',[APIController::class, 'getProfessor']);
    $router->get('/grade/:data',[APIController::class, 'getGrade']);
    $router->get('/materia/:data',[APIController::class, 'getMateria']);

    $router->post('/professor/:data',[APIController::class, 'registerProfessor']);
    $router->post('/grade/:data',[APIController::class, 'registerGradeHorario']);
    // $router->post('/grade/:data',[APIController::class, 'registerGrade']);

    $router->put('/professor/:data',[APIController::class, 'updateProfessor']);
    $router->put('/grade/:data',[APIController::class, 'updateGrade']);

    $router->delete('/professor/:data',[APIController::class, 'deleteProfessor']);
    $router->delete('/grade/:data',[APIController::class, 'deleteGrade']);
?>