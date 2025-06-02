<?php

namespace Http;

class Response{
    
    //codigo do status http
    private $httpCode = 200;

    //cabeçalho do respnse
    private $headers = [];

    //tipo de conteudo que esta sendo retornado
    private $contentType;

    //conteudo do reponse
    private $content;

    public function __construct($httpCode, $content, $contentType){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    //Método responsavel por alterar o content Type
    public function setContentType($contentType){
        $this -> contentType = $contentType;
        $this -> addHeader('Content-Type', $contentType);
    }

    //adiciona um registro do cabeçalho de response
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    //Método responsavel por enciar os headers para o navegador
    private function sendHeaders(){
        //define o STATUS
        http_response_code($this->httpCode);

        //enviar header
        foreach ($this->headers as $key=>$value){
            header($key.': '.$value);
        }
    }

    //Método responsavel por enviar a reposta para o usuario
    public function sendResponse(){
        //envia os headers
        $this->sendHeaders();

        //imprime o conteudo
        switch ($this->contentType){
            case 'text/html':
                print_r($this->content); 
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            case 'application/pdf':

                exit;
        }
    }
}