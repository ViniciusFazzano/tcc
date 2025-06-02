<?php
namespace Http\Middleware;
use \Utils\Cache\File as CacheFile;

class Cache{
    
    /**
     * método responsavek por verificar se requiest atual pode ser cacheada
     *
     * @param Request
     * @return boolean
     */
    private function isCacheable($request){
        //VALIDA O TEMPO DE CACHE
        if(getenv('CACHE_TIME') <= 0){
            return false;
        }

        //VALIDA O MÉTODO DA REQUISÇÃO
        if($request->getHttpMethod() != 'GET'){
            return false;
        }

        //VALIDA O HEADERS DE CACHE 
        $headers = $request->getHeaders();
        if(isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache'){
            return false;
        }

        //CACHEAVEL
        return true;
    }

    private function getHash($request){
        //URI da rota
        $uri = $request->getRouter()->getUri();

        //Query params
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';
        
        //remove as barras e retorana a hash
        return preg_replace('/[^0-9a-zA-Z]/','-',ltrim($uri,'/'));
    }

    /**
     * Método responsavel por executar o middeware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA SE A REQUEST É A CACHEAVEL
        if(!$this->isCacheable($request)) return $next($request);

        //Hash do cache
        $hash = $this->getHash($request);

        //Retorna os dados do cache
        return CacheFile::getCache($hash,getenv('CACHE_TIME'),function() use($request, $next){
            return $next($request);
        });
    }
}