<?php
require_once pathTo("src/model/Model.php");
require_once pathTo("src/model/Materia.php");
class MateriaLista extends Model{

    protected $fillable = [
        'professor_id',
        'materia_id'
    ];

    protected $encrypt = [];
    protected $predeterminated = [];

    public static function getMaterias($pID){
        $materias = MateriaLista::selectWhere([
            'field' => 'materia.nome, materia.id',
            'where'=>'professor_id = :pid',
            'params' =>[
                'pid'=> $pID
            ],
            'join'=>[
                'tableName' =>"materia",
                'on'=>'materia.id = materia_id'
            ]
        ]);
        $result = [];
        foreach ($materias->data() as $materia) {
            $result[$materia['id']] = $materia['nome'];
        }
        return $result;
    }

    public function __onInsert(){
        //Função magica, executada antes do Insert

    }
}
?>