<?php

namespace Http\Middleware;

class Queue{
    //mapeamento de middlewares que serão carregados em todas as rotas
    private static $default=[];

    //mapeamento de middlewares
    private static $map=[];

    //fila de middlewares
    private $middlewares = [];

    //função de execução do controlador
    private $controller;
    private $rolePermission;

    //argumentos da cunção do controlador
    private $controllerArgs;


    /**
     * Método responsável por construit a classe de fila de middlewares
     *
     * @param array $middlewares
     * @param Clousure $controller
     * @param array $controllerArgs
     */
    public function __construct($rolePermission, $middlewares, $controller,$controllerArgs)
    {
        $this -> middlewares = array_merge(self::$default, $middlewares);
        $this -> controller = $controller;
        $this -> controllerArgs = $controllerArgs;
        $this -> rolePermission = $rolePermission;
    }
    
    /**
     * Método reesponsavel por definir o mapeamento de middlewares
     *
     * @param [type] $map
     * @return void
     */
    public static function setMap($map){
        self::$map = $map;
    }

        /**
     * Método reesponsavel por definir o mapeamento de middlewares
     *
     * @param [type] $map
     * @return void
     */
    public static function setDefault($default){
        self::$default = $default;
    }


    /**
     * Método responsável por executar o proximo nivel da fila de middleware
     *
     * @param Request $request
     * @return Response
     */
    public function next($request){
        
        //Veridica se a fila esta vazia
        if(empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);

        //middleware   
        $middleware = array_shift($this->middlewares);
        
        //verifica o mapeamento 
        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas ao processar o middleware da requisição", 500);
        }
        
        //next
        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };

        //executa o middleware
        return (new self::$map[$middleware])->handle($request, $next, $this->rolePermission);

    }
} 