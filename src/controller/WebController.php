<?php
require_once "Controller.php";
class WebController extends Controller{
    
    function teste(){
        return[
            'view'=>'login/index'
        ];
    }
    function home($data){
        if ($data->middlewareResponse == null) {
            //If, for some reason the middleware response is null, that means the auth failed in some obscure way
            ErrorController::throw(array(
                'name'=>"500",
            ));
        }

        //This needs to be transfered to AUTH middleware
        $loggedUser = User::selectById($data->middlewareResponse['uid'])->data()[0];
        if ($loggedUser == null) {
            ErrorController::throw(array(
                'name'=>"404",
                'msg'=>"User not found"
            ));
        };

        setcookie('t',$data->middlewareResponse['token']);
        $turmas = Turma::selectWhere([
            'field'=>'curso.id as cursoId,curso.nome as curso, turma.id as turmaId,turma.nome as turma, ano',
            'where'=>'turma.id > 0',
            'params'=>[],
            'join'=>[
                'tableName'=>'curso',
                'on'=>'curso.id = curso_id'
            ]
        ]);
        $turmas = $turmas->hasError() ? null : $turmas->data();
        $cursos = [];
        foreach ($turmas as $turma) {
            $cursoID = $turma['cursoId'];
            isset($cursos[$cursoID]) ?  : $cursos[$cursoID] = ['nome'=>$turma['curso'],'turmas'=>[]];
            array_push($cursos[$cursoID]['turmas'],$turma);
        }
        return[
            'view'=>'home/index',
            'loggedUser'=> $loggedUser,
            'middlewareRes'=>$data->middlewareResponse,
            'cursos'=>$cursos ?? []
        ];
    }

    public function institucional($data){
        return[
            'view'=>'institucional/index',
            'middlewareRes'=>$data->middlewareResponse,
        ];
    }
}

?>