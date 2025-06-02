<?php

namespace Http;

class Request{ //gerencia a requisição que esta chegando
    
    //instancia do router
    private $router;

    private $httpMethod;

    private $uri;

    //Parametro da url ($_GET) 
    private $queryParams = [];

    private $postVars = [];

    private $headers = [];

    public function __construct()
    {
        // $this -> router = $router;
        $this -> queryParams = $_GET ?? [];
        $this -> setPostVars();
        $this -> headers = getallheaders();
        $this -> httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this -> uri = $_SERVER['REQUEST_URI'] ?? '';
        $this -> setUri();
    }

    //método responsavel por definir as variaveis do post
    public function setPostVars(){
        if($this->httpMethod == 'GET') return false;

        return $this->postVars = json_decode(file_get_contents('php://input'), true);
    }

    //método responsavel por definir a Uri
    private function setUri(){
        //URI completa(com GETS)
        $this -> uri = $_SERVER['REQUEST_URI'] ?? '';

        //REMOVE GETS DA URI
        $xURI = explode('?', $this->uri);

        $this -> uri = $xURI[0];
    }

    //metodo responsavel por retornar a instancia de Router
    public function getRouter(){
        return $this->router;
    }

    //retorna o metodo http da requisição
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    public function getUri(){
        return $this->uri;
    }

    //retorna os headers da requisição
    public function getHeaders(){
        return $this->headers;
    }

    //retorna os parametros da url da requisição
    public function getQueryParams(){
        return $this->queryParams;

    }

    public function getPostVars(){
        return $this->postVars;
    }
}