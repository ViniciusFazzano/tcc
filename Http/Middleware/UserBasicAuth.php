<?php
namespace Http\Middleware;

use \App\Model\Entity\User;

class UserBasicAuth{

    /**
     * Método responsavel por retornar uma instancia de usuario autenticado
     *
     * @return void
     */
    private function getBasicAuthUser(){
        //verifica a existencia dos dados de acesso
        if(!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])){
            return false;
        }

        //busca o usuario pelo email
        $obUser = User::getUserByLogin($_SERVER['PHP_AUTH_USER']);

        //verifica a instancia
        if(!$obUser instanceof User){
            return false;
        }

        // echo "<pre>";
        // print_r($obUser->password_user);
        // echo "</pre>"; exit;

        //valida senha e retorna o usuario
        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->password_user) ? $obUser : false;
    }

    /**método responsavel por validar o acesso via HTTP AUTH
     * @param Request $requst
     */
    private function basicAuth($request){
        //VERIFICA O USUARIO RECEBIDO
        if($obUser = $this->getBasicAuthUser()){
            // $request->user = $obUser;
            return true;
        }

        //emite o erro de senha invalida
        throw new \Exception("Usuário ou senha inválidos", 403);
    }

        /**
     * Método responsavel por executar o middeware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
        $this->basicAuth($request);
    
        return $next($request);
    }
}