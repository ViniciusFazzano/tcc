<?php

namespace Utils\Cache;

class File{

    /**
     * Método responsavel por obter uma informação do cache
     *
     * @param string $hash
     * @param integer $expiration
     * @param Closure $function
     * @return mixed
     */
    public static function getCache($hash,$expiration,$function){
        $content = $function();

        //retorna o conteudo
        return $content;
    }
}