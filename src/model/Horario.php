<?php
require_once pathTo("src/model/Model.php");
class Horario extends Model{

protected $fillable = [
    'nome',
    'turma_id',
    'professor_id',
    'materia_id'
];

protected $encrypt = [];
protected $predeterminated = [];

public static function getClasses($id){
    // select * from horario join turma on turma_id = turma.id join materia on materia_id = materia.id where professor_id = 1; 
    $horarios = Horario::selectWhere([
        'field'=>'horario.nome as horarioID,turma.nome as turma,turma_id,materia.nome as materia,materia_id',
        'where'=>'professor_id = :pid',
        'params'=>[
            'pid'=>$id
        ],
        'join'=>[
            [
                'tableName'=>'turma',
                'on'=>'turma_id = turma.id'
            ],
            [
                'tableName'=>'materia',
                'on'=>'materia_id = materia.id'
            ]
        ]
    ]);
    $result = [];
    if ($horarios->data() == null or $horarios->hasError()){ return [];}


    foreach ($horarios->data() as $horario) {
        $key = $horario['horarioID'];
        unset($horario['horarioID']);
        $result[$key] = $horario;
    }
    return $result;
}

public function __onInsert(){
    //Função magica, executada antes do Insert

}
}
?>