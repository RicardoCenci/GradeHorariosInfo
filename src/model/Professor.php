<?php 
require_once pathTo("src/model/Model.php");
require_once pathTo("src/model/Horario.php");
require_once pathTo("src/model/MateriaLista.php");
require_once pathTo("src/model/Curso.php");
require_once pathTo("src/model/Turma.php");

class Professor extends Model{

    protected $fillable = [
        'nome',
        'dataNasc',
        'blocked',
    ];

    protected $encrypt = [];
    protected $predeterminated = [];
    
    public function getProfessorData(){
        if ($this->data() == null) {
            return null;
        }
        $results = [];
        foreach ($this->data() as $result) {
            $id = $result['id'];
            $professor = $result; 
            $professor['subjects'] = MateriaLista::getMaterias($id);
            $professor['classes'] = Horario::getClasses($id);
            if (empty($professor['blocked'])) {
                $professor['blocked'] = [];
            }else{
                $professor['blocked'] = explode(',',$professor['blocked']);
            }
            array_push($results,$professor);
        }
        return $results;
    }
    public function __onInsert(){
        //Função magica, executada antes do Insert

    }
}
?>