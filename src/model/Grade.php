<?php 
require_once pathTo("src/model/Model.php");
class Grade extends Model{

protected $fillable = [
    'nome'
];

protected $encrypt = [];
protected $predeterminated = [];
public static function getPref(){
    $result = Grade::selectWhere([
        'where'=>'id > 1 ORDER BY ordem',
        'params'=>[]
    ])->data();
    return $result;
}
public function __onInsert(){
    //Função magica, executada antes do Insert

}
}
?>