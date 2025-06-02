<?php

namespace Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \Http\Middleware\Queue as MiddlewareQueue;

class Router{

    //url completa do projeto
    private $url = '';

    //prefixo de todas as rotas
    private $prefix = '';

    //indice de rotas
    private $routes = [];

    //instancia de request
    private $request;

    //content type do response
    private $contentType = 'application/json';
    
    //instancia
    private static $instances = 0;

    //metodo responsavel por iniciar a classe
    public function __construct($url)
    {
        self::$instances++;
        $this -> request = new Request();
        $this -> url = $url;
        $this -> setPrefix();
    }

    //instancias
    public static function getInstances() {
        return self::$instances;
    }

    public function setContentType($contentType){
        $this->contentType = $contentType;
    }

    //1-define o prefixo das rotas
    private function setPrefix(){
        //informações da url atual
        $parseUrl = parse_url($this->url);

        //define o prefixo (/Forcas)
        $this ->prefix = $parseUrl['path'] ?? '';
    }

    //3 - metodo responsavel por adicionar uma rota na classe
    private function addRoute($method, $route, $params=[]){
        //4-validação dos parametros
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        
        //middlewares da rota
        $params['middlewares'] = $params['middlewares'] ?? [];

        //Variaveis da rota
        $params['variables'] = [];

        //padrão de validação das variaveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //REMOVE BATTA NO FINAL DA ROTA
        $route = rtrim($route, '/'); 

        //5-padrão de validação da url
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

        //6 -adciona a rota dentro da classe
        $this ->routes[$patternRoute][$method] = $params;
    }

    //2 - responsavel por definir uma rota de GET
    public function get($route, $params=[]){
        // print_r($route);
        return $this->addRoute('GET', $route, $params);
    }

    //responsavel por definir uma rota de POST
    public function post($route, $params=[]){
        return $this->addRoute('POST', $route, $params);
    } 

    //responsavel por definir uma rota PUT
    public function put($route, $params=[]){
        return $this->addRoute('PUT', $route, $params);
    } 

    public function patch($route, $params=[]){
        return $this->addRoute('PATCH', $route, $params);
    } 

    //responsavel por definir uma rota DELETE
    public function delete($route, $params=[]){
        return $this->addRoute('DELETE', $route, $params);
    } 

    //11 - metodo responsavel por retornar a URI desconsirando o prefixo
    private function getUri(){
        //12 - URI da request(/FORCAS)
        $uri = $this -> request -> getUri();

        //13-fatia a uri com o prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //retorna a uri sem prefixo        
        return rtrim(end($xUri),'/');
    }

    //9-método responsavel por retornar os dados da rota atual
    private function getRoute(){
        //10 - uri
        $uri = $this ->getUri();
        
        //14-Method
        $httpMethod = $this->request->getHttpMethod();

        //15-valida as rotas
        foreach($this->routes as $patternRoute=>$methods){
            //verifica se a rota bate com o padrão
            if(preg_match($patternRoute, $uri, $matches)){
                //verifica o metodo
                if(isset($methods[$httpMethod])){
                    //removo a primeira posição
                    unset($matches[0]);

                    //chaves
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this -> request;

                    //retorno do parametros da rota
                    return $methods[$httpMethod];
                }
                throw new Exception("Método não permitido", 405);
            }
        }
        //url não encontrada
        throw new Exception("URL não encontrada", 404);
    }

    //7-responsavel por executar a rota atual
    public function run(){
        try{
            //8-obtem a rota atual
            $route = $this -> getRoute();

            //verifica o controlador
            if(!isset($route['controller'])){
                throw new Exception("A URL não pode ser processada", 500);
            }

            //argumentos da função
            $args = [];

            //reflection
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            
            //retorna a execução da fila
            return (new MiddlewareQueue($route['rolePermissao'], $route['middlewares'], $route['controller'], $args))->next($this->request);
        }catch(Exception $e){
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }    
    }

    //Método responsavel por retornar mesagem de erro de acordo com contentType
    private function getErrorMessage($message){
        switch($this->contentType){
            case 'application/json':
                return [
                    'error' => $message
                ];
            break;

            default:
                return $message;
            break;
        }
    }
}