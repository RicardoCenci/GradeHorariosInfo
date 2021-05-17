<?php
require_once pathTo("src/model/Model.php");
class Turma extends Model{

protected $fillable = [
    'nome',
    'curso_id',
    'ano'
];

protected $encrypt = [];
protected $predeterminated = [];

public function getGrade(){
    //from horario join professor on professor_id = professor.id join materia on materia.id = materia_id where turma_id = 1;
    $turma = Horario::selectWhere([
        'field' => 'horario.nome as horarioID, professor.nome as professor,professor.id as professorId, materia.color as cor, materia.nome as materia,materia.id as materiaId',
        'where' => 'turma_id = :tID',
        'params'=>[
            'tID'=>$this->data('id') ?? ''
        ],
        'join'=>[
            [
                'tableName' =>'materia',
                'on'=>'materia.id = materia_id'
            ],
            [
                'tableName' =>'professor',
                'on'=>'professor_id = professor.id'
            ]
        ]
    ]);
    $result = [];
    if ($turma->data() == null) {
        return [];
    }
    foreach ($turma->data() as $horario) {
        $key = $horario['horarioID'];
        unset($horario['horarioID']);
        $result[$key] = $horario;
    }
    if ($this->hasError()) {
        return [];
    }
    return $result;
}
public function __onInsert(){
    //Função magica, executada antes do Insert

}
}
?>