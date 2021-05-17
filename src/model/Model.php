<?php
class Model{
    protected $data;

    protected $fillable = [];
    protected $hidden = [];
    protected $predeterminated = [];

    protected $fields =[];

    public function data($s = ''){
        if ($this->data === NULL) {
            return NULL;
        }

        if ($s != '') {
            if (gettype($this->data) == 'array') {
                if($this->hasError()){
                    return $this->data;
                }
                return $this->data[0][$s] ?? $this->data[$s];
            }
        }
        return $this->data;
    }
    public function setData($data){
        if ($data instanceof PDOException) {
            return array(
                'code' => 1,
                'msg' => $data->getMessage()
            );
        }
        if ($data == NULL) {
            return NULL;
        }
        $this->data = $data;
    }

    //Selecionar por id
    public static function selectById($id){
        $model = self::getInstance();
        if (gettype($id) == 'array') {
            if(array_key_exists('code',$id)){
                $model->setData($id);
                return $model;
            };
        }

        $result = Database::select([
            'table'=>get_class($model),
            'where'=>'id = :id',
            'params'=>['id'=>$id]
        ]);

        if (empty($result)) {
            $model->setData(array(
                "code"=> 1,
                'msg'=> "Registro não encontrado",
            ));
           
        }
        $model->setData($result);
        return $model;
    }
    public function insertIfExists(){

    }
    // 'field' => 'materia.nome',
    // 'where'=>'professor.id = :pid'
    // 'params' =>[
    //     'pid'=>1
    // ],
    // 'join'=>[
    //     'tableName' =>"materia",
    //     'on'=>'materia.id = materia_id'
    // ]

    //Selecionar registros na tabela baseado em uma condição customizada;
    public static function selectWhere($condition = true){
        $model = self::getInstance();

        if (!array_key_exists('field',$condition)) {
            $condition['field'] = "*";
        }
        if (!array_key_exists('join',$condition)) {
            $condition['join'] = '';
        }
        $result = Database::select([
            'field'=>$condition['field'],
            'table'=>get_class($model),
            'where'=>$condition['where'],
            'params'=>$condition['params'],
            'join'=>$condition['join']
        ]);
        $model->setData($result);
        return $model;
    }

    public static function selectAll(){
        $model = self::getInstance();
        $model->setData(Database::selectAll([
            'table'=>get_class($model)
        ]));

        if($model->data() == NULL){
            $model->setData([
                'code' => 1,
                'msg'=> 'Registro não encontrado'
            ]);
        }
        return $model;
        
    }
    //Deletar registros na tabela
    public function delete(){
        if($this->hasError()){
            $this->setData([
                "code" => 1,
                "msg" => "Registro não encontrado"
            ]);
           return $this;
        }

        $result = Database::delete([
            'table'=>get_class($this),
            'where'=>'id=:id',
            'params'=>['id'=>intval($this->data('id'))]
        ]);

        $this->setData([
            "code" => 0,
            "msg" => "Registro deletado com sucesso",
        ]);
       return $this;
    }

    public function deleteAll(){
        if($this->data() == NULL or $this->hasError()){
            $this->setData([
                "code" => 1,
                "msg" => "Registro não encontrado"
            ]);
            return $this;
        }
        foreach ($this->data as $register) {
            $result = Database::delete([
                'table'=>get_class($this),
                'where'=>'id=:id',
                'params'=>['id'=>$register['id']]
            ]);
        }
        $this->setData([
            "code" => 0,
            "msg" => "Registros deletados com sucessos"
        ]);
        return $this;
    }

    //Inserir novos registros na tabela
    public function insert(){
        if($this->hasError()){
            return $this;
        }
        //Com certeza deve ter um jeito melhor de fazer isso
        $this->__onInsert();
        
        $newValues = [];
        foreach ($this->fields as $key => $value) {
            $newValues[$key] = $value;
        }
        // json($newValues);
        $fields= implode(",",array_keys($this->fields));
        $result = Database::insert([
            'table'=>get_class($this),
            'fields'=>$fields,
            'values' => $newValues,
        ]);
        $this->setData($result);
        return $this;
    }
    //Cria um modelo da tabela para ser inserida mais tarde pela funcão "insert"
    public static function createModel(array $info){
        $modelName = get_called_class();
        $model = new $modelName();
        if (!($model instanceof Model)) {
            return false;
        }
        $missingCamps = array_diff($model->fillable, array_keys($info));
        if (count($missingCamps) != 0) {
            $model->setData([
                    'code' => 2,
                    'msg' => "Campos obrigatorios não preenchidos",
                    'fields' =>$missingCamps
            ]);
            return $model;

        }
        foreach ($info as $newKey => $newValue) {
            $sanitizedValue = sanitizeInput($newValue);
            if (empty($sanitizedValue)) {
                //Só vai entrar aqui se o usuario mandar um payload de caracteres ilegais que consequentemente foram excluidas resultando
                //em uma string vazia que ira cair aqui.
                $model->setData([
                    'code' => 3,
                    'msg' => "Campo Vazio",
                    'field'=>$newKey
                    ]);
                return $model;
            }

            if (in_array($newKey,$model->encrypt)) {
                $model->fields[$newKey] = Database::hash($sanitizedValue);
            }else{
                $model->fields[$newKey] =  $sanitizedValue;
            }
        }
        return $model;
    }

    //Atualizar registros na tabela
    public function update($newValues){
        if($this->hasError()){
            return $this;
        }
        
        //Formatação para adicionar no DB
        $values = array();
        foreach ($newValues as $key => $value) {
            $v = sanitizeInput($value);
            $values[$key]= $v;
        }
        $result = Database::update([
            'table'=>get_class($this),
            'where'=>"id=:id",
            'values'=>$values,
            'params'=>[
                'id'=>intval($this->data('id')),
            ]
        ]);

        //Apesar de eu ja ter selecionado o usuario antes, tenho que
        //selecionar agora denovo pois os dados mudaram.
        return $this->selectById($this->data('id'));
    }

    public static function getInstance(){
        $modelName = get_called_class();
        $model = new $modelName();
        return $model;
    }
    public function __onInsert(){
        
    }
    public function hasError($var = ''){
        if(empty($var)){
            $var = $this->data();
        }
        if(gettype($var) == 'array'){
            if (array_key_exists('code',$var)) {
                if ($var['code'] != 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
?>