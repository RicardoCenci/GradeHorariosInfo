<?php
require_once "Controller.php";
class ErrorController extends Controller{

    public static function throw($err){
        $panic = new ErrorController();
        define('PANIC', true);
        echo(json_encode($err));
        exit;
        $viewInfo = array(
            'view'=> 'Error/'.$err['name'],
            'err'=>$err,
            'url'=>$_SERVER['REQUEST_URI'],
        );
        if(array_key_exists('title',$err)){
            $viewInfo['title'] = $err['title'];
        }else{
            $viewInfo['title'] = 'Ops... Algo deu errado :(';
        }
        $panic->view($viewInfo);
        die();
    }

}

?>