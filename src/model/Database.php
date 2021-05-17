<?php 
interface DatabaseInterface{

    static function TableExists(String $tableName = '');
    static function DropTable(String $tableName = '');
    static function createTable(String $sql);

    // GET
    function selectBy(object $params);
}
class MySQL implements DatabaseInterface{
    //Implementação de acesso ao banco de dados especifica para MySQL
    static public $me;//Instancia dessa classe
    private $DatabaseConfig;//Configurações do banco de dados
    protected $driver;//Driver = PDO
    public function __construct(){
        $cfg = APP_CONFIG["Database"]; //Todas configurações da DB;
        foreach ($cfg as $key => $value) {
            $this->{$key} = $value;
        }

    }
    protected function connect(){
        //Conecta na DB e retorna o driver PDO
        try{
            $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            return $conn;
        }catch(PDOException $e){
            ErrorController::throw(array(
                "error" => true,
                'name'=> "DBConnectError",
                'PDO'=>$e
            ));

        }
    }
    public static function TableExists(String $tableName = ''){
        //Checa se a tabela existe, retorna verdadeiro ou falso
        try {
            $db = Database::getDriver();
            $query = $db->prepare("SHOW TABLES LIKE '$tableName'");
            $query->execute();
            $result = $query->setFetchMode(PDO::FETCH_ASSOC);
            if (!empty($query->fetchAll())) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }
    public static function DropTable(String $tableName = ''){
        //Excluir a tabela
        try {
            $db = Database::getDriver();
            $query = $db->prepare("DROP TABLE $tableName");
            $query->execute();
            return true;
        } catch (PDOException $e) {
            print_r($e->getMessage());
            return false;
        }
    }
    public static function CreateTable(String $sql){
        //Cria Tabela baseado em uma query
        try {
            $db = Database::getDriver();
            $db->exec($sql);
        } catch (PDOException $e) {
            print_r($e->getMessage());
            return false;
        }
    }
    public function selectBy(object $params){
        //Quase certeza que essa função é legacy e eu não uso mais
        if (!isset($params->value)) {
            return false;
        }
        if (!isset($params->operator)) {
            $params->operator= "=";
        }
        if(!isset($params->type)){
            $params->type = "id";
        }

        try{
        $db = Database::getDriver();
        $sql = 'SELECT * FROM '.strval(get_class($params->model)).' where '.strval($params->type).''.strval($params->operator).''.strval($params->value).'';
        $query = $db->prepare($sql);
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $params->model->setData($query->fetchAll());
        return $params->model;
        }catch (PDOException $e) {
            print_r($e->getMessage());
            return false;
    }
    }

    // 'field' => '*',
    // 'where'=>'professor_id = :pid'
    // 'params' =>[
    //     'pid'=>1
    // ],
    // 'join'=>[
    //     [
    //         'tableName' =>"turma",
    //         'on'=>'turma_id = turma.id'
    //     ],
    //     [
    //         'tableName' =>"materia",
    //         'on'=>'materia_id = materia.id'
    //     ]
    // ]
    // select * from horario join turma on turma_id = turma.id join materia on materia_id = materia.id where professor_id = 1;
    private static function joinString(&$joinInfo){
        if (!is_array($joinInfo)) {
            return '';
        }
        if (isset($joinInfo[0])) {
            //If its an array of arrays.
            if(is_array($joinInfo[0])){
                $joinString = '';
                foreach ($joinInfo as $joinStatement) {
                    $joinStatement['type'] = isset($joinStatement['type']) ? $joinStatement['type'] : 'JOIN';
                    if (!array_key_exists('tableName',$joinStatement) or !array_key_exists('on',$joinStatement)){
                        continue;
                    }
                    $joinString = $joinString." {$joinStatement['type']} {$joinStatement['tableName']} on {$joinStatement['on']}";
                }
                $joinInfo = $joinString;
                return;
            };
        }else{
            if (!array_key_exists('tableName',$joinInfo) or !array_key_exists('on',$joinInfo)) {
                return '';
            }
            if (!array_key_exists('type',$joinInfo)) {
                $joinInfo['join']['type'] = 'JOIN';
            }
        }
        $joinInfo = "{$joinInfo['join']['type']} {$joinInfo['tableName']} on {$joinInfo['on']}";
    }
    public static function select($queryInfo){
        if (!array_key_exists('field',$queryInfo)) {
           $queryInfo['field'] = "*";
        }
        if (!array_key_exists('join',$queryInfo)) {
            $queryInfo['join'] = '';
        }else{
            self::joinString($queryInfo['join']);
        }
        $sql = "SELECT {$queryInfo['field']} FROM &table {$queryInfo['join']} WHERE {$queryInfo['where']}";
        return self::executeQuery($sql,$queryInfo);
    }

    public static function delete($queryInfo){
        $sql = "DELETE FROM &table WHERE {$queryInfo['where']}";
        return self::executeQuery($sql,$queryInfo,false);
    }

    public static function insert($queryInfo, $onUpdate ='', $debug = false){
        $keys = array();
        foreach ($queryInfo['values'] as $key => $value) {
            array_push($keys,':'.$key);
            $queryInfo['params'][$key] = $value;
        }
        $keys = implode(',',$keys);
        $sql = "INSERT INTO &table({$queryInfo['fields']}) values({$keys})";
        $result = self::executeQuery($sql,$queryInfo,false);
        
        if (gettype($result) != 'boolean'){
            if(get_class($result) == 'PDOException') {
            if ($debug) {
                return[
                    'code'=>1,
                    'debugInfo' => [
                        "code"=>$result->getCode(),
                        "msg"=>$result->getMessage()
                    ],
                ];
            }else{
                return [
                    'code'=> 1,
                    'msg'=>'Algo deu errado na insersão de registros',
                    'err'=> $result->getCode() ?? " "
                ];
                }
            }
        }
        if($result){
            return [[
                'code'=> 0,
                'msg'=>'Registro adicionado com sucesso',
                'id' =>Database::lastInsertId(),
            ]];
        }else{
            return [[
                'code'=> 1,
                'msg'=>'Algo deu errado na insersão de registros',
                'response'=>$result
            ]];
        }
        return PDO::lastInsertId();
}
    public static function update($queryInfo){
        $newValues = array();
        foreach ($queryInfo['values'] as $key => $value) {
            array_push($newValues,$key.'=:'.$key);
            $queryInfo['params'][$key] = $value;
        }
        $newValues = implode(',',$newValues);
        $sql ="UPDATE &table SET $newValues where {$queryInfo['where']}";
        return self::executeQuery($sql,$queryInfo,false);
    }
    public static function selectAll($queryInfo){
        $sql = "SELECT * FROM &table";
        return self::executeQuery($sql,$queryInfo);
    }
    

    public static function date($str, $format = 'd-m-Y'){

        if ($str == null) {
            return null;
        }
        $date= date_create($str);
        if (!$date) {
            return null;
        }
        $date = date_format($date,"Y-m-d");
        return $date;
    } 
    
    private static function lastInsertId(){
        $db = Database::getDriver();
        return $db->lastInsertId();
    }
    
    private static function executeQuery($sql,$queryInfo,$fetchAll = true){
        $db = Database::getDriver();
        $tableName = APP_CONFIG['Database']['tablePrefix'].$queryInfo['table'];
        $sql = str_replace('&table',$tableName,$sql);
        try {
            $query = $db->prepare($sql);
            if(array_key_exists('params',$queryInfo)){
                foreach ($queryInfo['params'] as $key => $value) {
                    $query->bindValue(':'.$key,$value);
                }
            }
            $query->execute();

            if ($fetchAll) {
                $result = $query->setFetchMode(PDO::FETCH_ASSOC);
                return $query->fetchAll();
            }else{
                return true;
            }
        } catch (PDOException $e) {
            return $e;
        }
    }
}
class Database extends MySQL
{
    public function  __construct(){
        //Chama o construtor do pai que ira setar as configurações
        //Depois chama o metodo connect que ira conter a logica para conectar no driver escolido
        parent::__construct();
        $this->driver = $this->connect();
        Database::$me = $this; //Salva a instancia do objeto para mais tarde
    }
    public static function getInstance(){
        //Retorna uma instancia ativa da classe, se ela existir;
        //Pra mim nao ter q instanciar ela mais pra frente
        if (!(Database::$me instanceof Database)) {
            Database::$me = new Database();
        }
        return Database::$me;
    }
    public static function getDriver(){
        $db = Database::getInstance();
        return $db->driver;
    }
    public static function sanitize($string){
        return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }
    public static function hash($s){
        return password_hash($s, PASSWORD_DEFAULT,['cost' =>11]);
    }
}
?>