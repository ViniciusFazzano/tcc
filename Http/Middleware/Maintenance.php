<?php

namespace http\Middleware;

class maintenance{
        /**
     * Método responsavel por executar o middeware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //verifica o estado de manutenção da pagina
        if(getenv('MAINTENANCE') == 'true'){
            throw new \Exception("Pagina em manutenção. Tente novamente mais tarde", 200);
        }
        return $next($request);
    }
}