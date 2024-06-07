<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\BancoDeDados;

/**
* Executa operações de atualização no banco de dados.
 *
 * @author c068442
 */
class Update extends BancoDeDados
{
    
    /**
     * Inicia a conexão com o banco de dados.
     */
    public function __construct()
    {
        self::conectarBanco();
    }
    
    
    /**
    * <p>Monta uma query UPDATE com base nos parametros fornecidos.</p>
    *
    * @param string $Tabela <p>Tabela a ser atualizada</p>
    * @param string $PrimaryKey <p>Identificador único da tabela.</p>
    * * @param array $Parametros <p>Lista de array's, contendo [Campo, Condição, Valor]</p>
    * @return string
    */
    public function montarUpdate(string $Tabela, string $PrimaryKey, string $ValorPrimaryKey, array $Parametros): string
    {
        $Campos = '(';
        foreach ($Parametros as $Parametro) {
            $Campo = trim($Parametro[0]);
            $Valor = $this->verificarValor($Parametro[2]);
            switch (true) {
                case $Valor === false:
                    break;
                case is_null($Valor):
                default:
                    $Campos .= "{$Campo}, ";
                    break;
            }
        }
        return "UPDATE {$Tabela} {$this->montarSet($Parametros)} {$this->montarWhere([[$PrimaryKey, '=', $ValorPrimaryKey]])}";
    }
    
    
    
    
    /**
     * <p>Monta a cláusula SET de um UPDATE</p>
     *
     * @param array $Parametros <p>Lista de array's, contendo [Campo, Condição, Valor]</p>
     * @return string
     */
    public function montarSet(array $Parametros): string
    {
        $SET = ' SET ';
        foreach ($Parametros as $Parametro) {
            $Campo = trim($Parametro[0]);
            $Valor = $this->verificarValor($Parametro[2]);
            switch (true) {
                case $Valor === false:
                    break;
                case is_null($Valor):
                    $SET .= "{$Campo} = NULL, ";
                    break;
                default:
                    $SET .= "{$Campo} = :{$Campo}, ";
                    break;
            }
        }
        return substr($SET, 0, - 2) . ' ';
    }
}

//PHP EOF
