<?php
require pathTo('/vendor/autoload.php');
require pathTo('src/controller/ErrorController.php');
require pathTo('src/controller/WebController.php');
require pathTo('src/controller/APIController.php');
require pathTo('src/router/Request.php');
require pathTo('src/middleware/Auth.php');
class Router{
    private $routes = [];
    private $request;
    protected $middleware;
    protected $supportedHttpMethods =[
        'GET',
        'POST',
        'DELETE',
        'PUT'
    ];


    public function __construct(){
        $this->request = new Request();
    }

    public function __call($name, $args){
        $route = $args[0];
        list($class,$method) = $args[1];
        if(!in_array(strtoupper($name), $this->supportedHttpMethods))
        {
            $this->invalidMethodHandler();
        }
        $urlData = $this->isRouteData($route);
        if ($urlData) {
            $route =  $this->getDataFromUrl($route);
            $route = $route[0];
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = [$class,$method,$urlData, $this->middleware];

    }
    function uses($middlewareObj, $middlewareMethod = false){
        if (gettype($middlewareObj) == 'object') {
            if (get_class($middlewareObj) == 'Router') {
                $this->middleware = false;
                return;
            }
        }
        if ($middlewareMethod == null) {
            ErrorController::throw(array(
                'name'=>"501",
            ));
            return;
        }
        $this->middleware = [$middlewareObj, $middlewareMethod];

    }
    private function invalidMethodHandler()
    {

      ErrorController::throw(array(
          'name'=>"405",
          "msg"=>"Method Not allowed"
      ));
    }
  
    private function defaultRequestHandler()
    {
      ErrorController::throw(array(
        'name'=>"404",
        'msg'=>"No registred route to ".$this->request->RequestUri
    ));
    }

    private function isRouteData($route){
        $result = explode("/:",$route);
        if(count($result) != 1){
            //Rota tem dados dinamicos
            return true;
        }else{
            return false;
        }
    }
    private function getDataFromUrl($url){
        $actualRoot = $this->getRootWithoutPrefix();

        $result = str_replace($actualRoot,'/',$url);
        $result = explode("/",$result);
        $possibleData = end($result);
        $result = str_replace($possibleData,'',$result);
        $possibleData = sanitizeInput($possibleData);
        return [implode('/',$result),$possibleData];
    }
    
    private function getRootWithoutPrefix(){
        //Arruma o bug de quando você não esta rodando o site no ROOT do apache e sim de dentro de uma pasta.
        $pathToIndex = $this->request->ScriptName; //ScriptName sempre ira retornar /RootFolder/index.php, tudo antes do index é considerado ROOT
        $actualRoot = str_replace('index.php','',$pathToIndex);
        return $actualRoot;
    }

    private function formatRoute($route)
     {
        $actualRoot = $this->getRootWithoutPrefix(); //Arruma o bug de quando você não esta rodando o site no ROOT do apache e sim de dentro de uma pasta.
        $result = str_replace($actualRoot,'/',$route);

        $result = explode("/:",$result);
        $result = rtrim($result[0], '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }
    function resolve(){
        $methodDictionary = $this->{strtolower($this->request->RequestMethod)};
        $formatedRoute = $this->formatRoute($this->request->RequestUri);
        $notFound = true;

        foreach ($methodDictionary as $route => $routeController) {
            $thereIsRouteData = $routeController[2];
            if($thereIsRouteData){
                list($url,$data) = $this->getDataFromUrl($this->request->RequestUri);
                $url = $this->formatRoute($url);
                if(array_key_exists($url,$methodDictionary)){
                    if ($url == '/') {
                        //Aparentemente quando tu hita um url que não existe ele formata a url para '/'
                        //Assim chamando o controller home
                        //Pode ser esse um dos problemas de duplicação de dados na db que eu tava tendo
                        //Não é a solução que eu queria fazer mas é solução que deve ser feita
                        if(array_key_exists($data,$methodDictionary)){
                            continue;
                        }else{
                            break;
                        }
                    }
                    //Dispatch
                    //checa se a url tem uma rota e dispacha
                    $notFound = false;
                    $controllerList= $methodDictionary[$url];
                    list($controllerName,$method, ,$middleware) = $controllerList;

                    if(is_null($method))
                    {
                        $this->defaultRequestHandler();
                        return;
                    }
                    $this->dispatch($controllerName,$data,$method,$middleware);
                    break;
                }
            }else{
                if(array_key_exists($formatedRoute,$methodDictionary)){
                     //Dispatch
                    //checa se a url tem uma rota e dispacha
                    $data = null;
                    $notFound = false;
                    $controllerList= $methodDictionary[$formatedRoute];
                    list($controllerName,$method, ,$middleware) = $controllerList;
                    $this->dispatch($controllerName, $data, $method, $middleware);
                    break;
                }
            }
        }

        if($notFound){;
            $this->defaultRequestHandler();
            return;
        }
 
    }
    private function dispatch($controllerName,$data,$method, $middleware){
        if(!class_exists($controllerName)){
            ErrorController::throw([
                'name'=>'501'
            ]);
        }

        $controller = new $controllerName();//Instancia a classe controladora

        if(!method_exists($controller,$method)){
            ErrorController::throw([
                'name'=>'501'
            ]);
        }




        $dataToController = $this->request;
        $dataToController->{'urlData'} = $data;
        $dataToController->{'middlewareResponse'} = null;

        //Pass everything to the middleware to be processed before passing to the controller

        if (gettype($middleware) == 'array') {
            list($middlewareObj, $middlewareMethod) = $middleware;
            $mid = new $middlewareObj($this->request);
            $dataToController->{'middlewareResponse'} = $mid->$middlewareMethod();
        }
    
        $info = $controller->$method($dataToController);//Passa pro controle os dados da URL

        $controller->view($info);//Passa as informações procesadas do controlador para a visão
    }
    function __destruct()
    {   
        if (defined('PANIC')) {
            exit();
        }
        $this->resolve();
    }
    public static function redirectTo($route){
        header("Location:".url($route.""));
        exit(0);
    }
}

?>