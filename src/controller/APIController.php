<?php
require_once "Controller.php";
require_once pathTo("src/model/Professor.php");
require_once pathTo("src/model/Grade.php");
class APIController extends Controller{
    
    function getProfessor($data){
        if ($data->urlData == null or $data->urlData == 0) {
            $professor = Professor::selectAll();
        }else{
            $id = $data->urlData;
            $professor = Professor::selectById($id);
        }
        if ($professor->hasError()) {
            return [
                'view'=> 'json',
                'return' => [
                    'code'=>404,
                    'msg'=>'No professor found with this id'
                ]
            ];
            exit;
        };
        if ($data->urlData === '0') {
            $response = [];
            foreach ($professor->data() as $professorObj) {
                if (empty($professorObj['blocked'])) {
                    $professorObj['blocked'] = [];
                }else{
                    $professorObj['blocked'] = explode(',',$professorObj['blocked']);
                }
                array_push($response, $professorObj);
            }
            return [
                'view'=> 'json',
                'return' => [
                    $response
                ]
            ];    
        }
        $result = $professor->getProfessorData();
        return [
            'view'=> 'json',
            'return' => [
                $result
            ]
        ];
    }
    function getGrade($data){
        if ($data->urlData == null) {
            $result = GRADE::getPref();
            return [
                'view'=> 'json',
                'return' => [
                    $result
                ]
            ];
        }
        $result = Turma::selectById($data->urlData);
        if ($result->hasError() or $result->data() == null) {
            return [
                'view'=> 'json',
                'return' => [
                    'code'=>1,
                    "msg"=>"Turma not found"
                ]
            ];
        }
        $grade = $result->getGrade();
        return [
            'view'=> 'json',
            'return' => [
                $grade
            ]
        ];
    }

    public function registerGradeHorario($data){
        $requestData = $data->jsonBody;
        if (empty($requestData)) {
            return [
                'view'=> 'json',
                'return' => [
                    'Data is missing'
                ]
            ];
        }
        $turma = sanitizeInput($data->urlData) ?? '';
        if(empty($turma)){
            return [
                'view'=> 'json',
                'return' => [
                    'code'=> 400,
                    'msg'=>'Turma not especified'
                ]
            ];
        }
        $result = Turma::selectById($turma);
        if ($result == null or $result->hasError()) {
            return [
                'view'=> 'json',
                'return' => [
                    'code'=>404,
                    'msg'=>'turma not found'
                    ]
                ];
            }
        $result = Horario::selectWhere([
            'where'=>'turma_id = :tId',
            'params'=>[
                'tId'=>$turma
            ]
        ]);
        $horarios = [];
        foreach ($result->data() ?? [] as $key) {
            $horarios[$key["nome"]] = $key;
        }
        $horariosFromDBKeys = array_keys($horarios);
        $requestKeys = array_keys($requestData);
        $toBeDeleted = array_diff($horariosFromDBKeys, $requestKeys);
        foreach ($toBeDeleted as $horarioToDelete) {
            $result = Horario::selectWhere([
                'where'=>'nome = :nome and turma_id = :turmaId',
                'params'=>[
                    'nome'=>$horarioToDelete,
                    'turmaId'=>$turma
                ]
            ]);
            $result->delete();
        }
        foreach ($requestData as $key => $horario) {

            if (in_array($key ?? '' , $horariosFromDBKeys)) {
                //if the key exists, updates it
                $result->setData([
                    "id"=>$horarios[$key]['id']
                ]);
                $result->update([
                    'turma_id'=>$turma,
                    'professor_id'=>$horario['professorId'] ?? '',
                    'materia_id'=>$horario['materiaId'] ?? '',
                ]);
            }else{
                //If the key doesnt exists, inserts it
                $result = Horario::createModel([
                    'nome'=> $key ?? "",
                    'turma_id'=> $turma ?? "",
                    'professor_id'=> $horario['professorId'] ?? "",
                    'materia_id'=> $horario['materiaId'] ?? "" 
                ])->insert(); 
            } 
        }
        return [
            'view'=> 'json',
            'return' => [
                $result->data()[0] ?? $result->data()
            ]
        ];
    }
    public function getMateria($data){
        if ($data->urlData == null) {
            $result = Materia::selectAll();
        }else{
            $result = Materia::selectById($data->urlData);
        }
        if ($result->hasError()) {
            return [
                'view'=> 'json',
                'return' => [
                    'No materia found'
                ]
            ];
        }
        return [
            'view'=> 'json',
            'return' => [
                $result->data()
            ]
        ];
    }


    public function registerProfessor($data){
        $request = $data->requestBody();
        if (empty($request)) {
            return [
                'view'=> 'json',
                'return' => [
                    'Data is missing'
                ]
            ];
        }
        $professor = Professor::createModel([
            'nome'=> $request['nome'] ?? '',
            'dataNasc'=>Database::date($request['dataNasc'] ?? false),
            'blocked'=> $request['blocked'] ?? ' '
        ])->insert();
        return [
            'view'=> 'json',
            'return' => [
                $professor->data()[0] ?? $professor->data()
            ]
        ];
    }
    public function updateProfessor($data){
        $request = $data->jsonBody;
        if (empty($request)) {
            return [
                'view'=> 'json',
                'return' => [
                    'Data is missing'
                ]
            ];
        }
        $professor = Professor::selectById($data->urlData);
        if ($professor->hasError()) {
            return [
                'view'=> 'json',
                'return' => [
                    'code' => 404,
                    'msg' => 'Register not found'
                ]
            ];
        }
        $new = $professor->update([
            'nome'=> $request['nome'] ?? $professor->data('nome'),
            'dataNasc' => Database::date($request['dataNasc'] ?? null) ?? $professor->data('dataNasc'),
            'blocked' => $request['blocked'] ?? $professor->data('blocked')
        ]);
        json($new->data());
        return [
            'view'=> 'json',
            'return' => [
                $professor->data()[0] ?? $professor->data()
            ]
        ];
    }
    public function updateGrade(){}
    public function deleteGrade(){}
    public function deleteProfessor(){}


}

?>