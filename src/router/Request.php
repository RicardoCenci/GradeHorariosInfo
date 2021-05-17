<?php
class Request{
    
    public function __construct(){
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
        $this->RequestUri = sanitizeInput($this->RequestUri);
        $unsafeJson = json_decode(file_get_contents('php://input')) ?? [];
        $safeJson = [];
        foreach ($unsafeJson as $key => $value) {
           $safeJson[$key] = sanitizeInput($value);
        }
        $this->jsonBody = $safeJson ?? [];
    
    }

    private function toCamelCase($str){
        //Transform the param in from snake_case to camelCase
        $str = strtolower($str);
        return str_replace("_", '', ucwords(ucwords($str, "_")));
    }

    public function requestBody(){
        if ($this->RequestMethod == "GET") {
            return;
        }
        if ($this->RequestMethod == "POST") {
            //Retorna o corpo da requisição
            $body = array();
            foreach ($_POST as $key => $value) {
                $body[$key] = sanitizeInput($value);
            }
            return $body;
        }
        
    }
}

?>