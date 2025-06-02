<?php
namespace Http\Middleware;

use \App\ClientRegisterInit\Model\LoginModel;
use App\Infra\Usuario\UsuarioPdo;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function PHPUnit\Framework\throwException;

class JWTAuth{

    /**
     * Método responsavel por retornar uma instancia de usuario autenticado
     * @param Request $request
     * @return void
     */
    private function getJWTAuthUser($request, $rolePermission){
        $headers = $request->getHeaders();
        
        //toke puro em jwt
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

        try{
            //decode
            $decode = (array)JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));
        }catch(\Exception $e){
            throw new \Exception("Token invalido", 403);
        }
        $login = $decode['login'] ?? '';
        
        $usuario = new UsuarioPdo();
        //busca o usuario pelo login
        $usuario->setTable("tb_usuario");
        $permissao =  $usuario->selectPadrao("login = ?", "permissao", null, null, [$login], null)->fetchColumn();
        
        if(!empty($rolePermission)){
            if(in_array($permissao, $rolePermission)){
                return true;
            }else{
                throw new \Exception("Você não tem acesso!");
            }
        }else{
            throw new \Exception("Você não tem acesso!");
        }
    }

    /**método responsavel por validar o acesso via JWT
     * @param Request $requst
     */
    protected function auth($request,  $rolePermission){
        //VERIFICA O USUARIO RECEBIDO
        if($obUser = $this->getJWTAuthUser($request, $rolePermission)){ 
            return $obUser;
        }
        
        //emite o erro de senha invalida
        throw new \Exception("Acesso negado", 403);
    }
        /**
     * Método responsavel por executar o middeware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next,  $rolePermission){
        //REALIZA A VALIDAÇÃO DO ACESSO VIA JWT
        $this->auth($request, $rolePermission);


        return $next($request);
    }
}
