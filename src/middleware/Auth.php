<?php
require pathTo('src/model/User.php');
require pathTo('src/model/Database.php');
use ReallySimpleJWT\Token;

class Authenticator{
    private $request;
    function __construct($request){
        $this->request = $request;
    }
    function auth(){
        $requestBody = $this->request->requestBody();
        $auth= false;

        // User::createModel([
        //     'name' => "Ricardo Cenci Fabris",
        //     'email'=> 'ricardocencifabris@gmail.com',
        //     'password'=>'123'
        // ])->insert();

        if (property_exists($this->request, 'RedirectHttpAuthorization')){

            $authHeader = $this->request->RedirectHttpAuthorization;

            if (!preg_match('/Bearer\s(\S+)/', $authHeader , $matches)) {

                if($matches[1]){
                    //If the key is not empty
                    $auth = true;
                    $token = $matches[1];
                }
            }
        }
        if(array_key_exists("t",$_COOKIE)){
           $auth = true;
           $token = $_COOKIE['t'];
        }


        if (!$auth) {
            //If the user did not submit a token, try to login with form data 
            $payload = $this->tryLogin();
            if ($payload == null) {
                header('Location:'.APP_URL.'/login');
                exit();
            }
            return $payload;
        }else{
            //If the user submits a token, execute this
            $result = Token::validate($token,APP_SECRET);
            if (!$result) {
                //Invalid token, go back to login screen
                header('Location:'.APP_URL.'/login');
                exit();
            }
            $payload = Token::getPayload($token,APP_SECRET);
            $payload["token"] = $token;
            return $payload;
        }  
    }
    public function APIAuth(){
        // $requestBody = $this->request->requestBody();
        // if (property_exists($this->request, 'RedirectHttpAuthorization')){
        //     $authHeader = $this->request->RedirectHttpAuthorization;
        //     if (!preg_match('/Bearer\s(\S+)/', $authHeader , $matches)) {
        //         if($matches[1]){
        //             //If the key is not empty
        //             $auth = true;
        //             $token = $matches[1];
        //         }
        //     }
        // }
        // if (!$auth) {
        //     return false;
        // }
    }
    private function tryLogin(){
        //Try to do login
        $data = $this->request->requestBody();
        if ($data == null) {
            header('Location:'.APP_URL.'/login');
            exit();
        }
        if (!array_key_exists('lgn',$data) and !array_key_exists('psw',$data)) {
            header('Location:'.APP_URL.'/login');
            exit();
        }

        $response = User::authenticate([
            "name"=>sanitizeInput($data['lgn']),
            "password"=>sanitizeInput($data['psw'])
        ]);
        
        if (!$response['success']) {
            header('Location:'.APP_URL.'/login');
            exit();
        }
        $payload = [
            "uid" => $response['uid'],
            "createdAt"=> time()
        ];

        try{
            $token = Token::customPayload($payload,APP_SECRET);
        }catch(ReallySimpleJWT\Exception\BuildException $e){
            ErrorController::throw(array(
                'name'=>"500",
            ));
            exit;
        }
        $payload['token'] = $token;
        return $payload;
    }
}


?>