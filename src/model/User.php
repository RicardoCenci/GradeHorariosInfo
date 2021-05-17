<?php 
require_once pathTo("src/model/Model.php");

class User extends Model{

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $encrypt = [
        'password'
    ];
    protected $predeterminated = [];

    public static function authenticate($params){
        $user = User::selectWhere([
            'where' => 'name = :name',
            'params'=>[
                'name'=>$params['name']
            ]
        ]);
        if ($user->data() == null) {
            return false;
        }

        return [
            'uid' => $user->data()[0]['id'],
            "success" => password_verify($params['password'],$user->data()[0]['password'])
        ];
    }

    public function __onInsert(){
        //Função magica, executada antes do Insert
        // $this->fields['confirmationToken'] = generateRandomString(100);
    }
}
?>