<?php
require_once pathTo("src/model/Model.php");
class Materia extends Model{

protected $fillable = [
    'nome'
];

protected $encrypt = [];
protected $predeterminated = [];

public function __onInsert(){
    //Função magica, executada antes do Insert

}
}
?>