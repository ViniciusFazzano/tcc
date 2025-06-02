<?php
namespace App\Utils;

class View{

    public static function manda($vars = []){
        $result = json_encode($vars);
        return $result;
    }
}