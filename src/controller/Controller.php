<?php
class Controller{
    
    public function view($data){
        if(gettype($data) != 'array'){
            //Caso o dado passado pra visão não for um array
            ErrorController::throw([
                'name'=> '501',
                'msg'=>'Wrong type of data to View'
            ]);
        }
        if(!array_key_exists('view',$data)){
            //Caso o caminho para o arquivo da visão não esteja declarado
            ErrorController::throw([
                'name'=> '501',
                'msg'=>'View Path not declared'
            ]);
        };
        if ($data['view'] == 'json') {
            if(gettype($data['return']) == 'array'){
                $sanitizedOutput = sanitizeOutputArray($data['return']);
            }else{
                $sanitizedOutput = sanitizeOutput($data['return']);
            }
            if (isset($sanitizedOutput[0])) {
                echo json_encode($sanitizedOutput[0]);
            }else{
                echo json_encode($sanitizedOutput);
            }
            exit;
        }

        $fileURL = pathTo("src/View/{$data['view']}.php");
        if(!file_exists($fileURL)){
            //Caso o arquivo de visão não for encontrado
            ErrorController::throw([
                'name'=> '501',
                'msg'=>'View not found in path "'.$fileURL.'"'
            ]);
        }
        $sanitizedData = [];
        $view = $data['view'];
        unset($data['view']); //dou unset na view para ela não ir para o array que vai para a visão.
        if(!array_key_exists('err',$data)){
            foreach ($data as $key => $value) {
                $sanitizedData[$key] = sanitizeOutput($value);
            }
        }else{
            $sanitizedData = $data;
        }
        $this->callViewPage($view,$sanitizedData); //Cria um contexto para as variaveis da pagina.
        die();
    }
    private function callViewPage($page, $data){
        extract($data, EXTR_OVERWRITE);
        require_once pathTo("src/View/{$page}.php");
    }
}

?>