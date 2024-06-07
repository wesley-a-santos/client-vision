<?php

//namespace Model;

//use \PDO;

class Conexao 
{
    public static function pegarConexaoExterno()
    {
        $conexao = new PDO("sqlsrv:server=bdd5517wn001 ; Database=Externo", "user", "senha") or die ("Não foi possível conectar.");
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    }
    
    
} 