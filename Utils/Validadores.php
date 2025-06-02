<?php
namespace Utils;

class Validadores{
        // Validar numero de CNPJ
        static public function validaCNPJ($cnpj)
        {
            // Remove caracteres não numéricos do CNPJ
            $cnpj = preg_replace('/\D/', '', $cnpj);
    
            // Verifica se o CNPJ possui 14 dígitos
            if (strlen($cnpj) !== 14) {
                return false;
            }
    
            // Verifica se todos os dígitos são iguais, o que é inválido para CNPJ
            if (preg_match('/^(\d)\1+$/', $cnpj)) {
                return false;
            }
    
            // Validação dos dígitos verificadores
            $tamanho = strlen($cnpj) - 2;
            $numeros = substr($cnpj, 0, $tamanho);
            $digitos = substr($cnpj, $tamanho);
            $soma = 0;
            $pos = $tamanho - 7;
    
            for ($i = $tamanho; $i >= 1; $i--) {
                $soma += $numeros[$tamanho - $i] * $pos--;
                if ($pos < 2) {
                    $pos = 9;
                }
            }
    
            $resultado = $soma % 11 < 2 ? 0 : 11 - ($soma % 11);
            if ($resultado != $digitos[0]) {
                return false;
            }
    
            $tamanho += 1;
            $numeros = substr($cnpj, 0, $tamanho);
            $soma = 0;
            $pos = $tamanho - 7;
    
            for ($i = $tamanho; $i >= 1; $i--) {
                $soma += $numeros[$tamanho - $i] * $pos--;
                if ($pos < 2) {
                    $pos = 9;
                }
            }
    
            $resultado = $soma % 11 < 2 ? 0 : 11 - ($soma % 11);
            if ($resultado != $digitos[1]) {
                return false;
            }
    
            // CNPJ válido
            return true;
        }
    
    
        static public function validaCPF($cpf)
        {
    
            // Extrai somente os números
            $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    
            // Verifica se foi informado todos os digitos corretamente
            if (strlen($cpf) != 11) {
                return false;
            }
    
            // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
    
            // Faz o calculo para validar o CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
        }
    
}