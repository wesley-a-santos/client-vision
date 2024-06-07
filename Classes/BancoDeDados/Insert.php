<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\BancoDeDados;

/**
 * Executa operações de inserção no banco de dados.
 *
 * @author c068442
 */
class Insert extends BancoDeDados
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
    public function montarInsert(string $Tabela, string $PrimaryKey, array $Parametros): string
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
        return "INSERT INTO {$Tabela} {$this->finalizarQuery($Campos)} OUTPUT INSERTED.{$PrimaryKey} {$this->montarValues($Parametros)}";
    }

    /**
     * <p>Monta a cláusula VALUE de um INSERT</p>
     *
     * @param array $Parametros <p>Lista de array's, contendo [Campo, Condição, Valor]</p>
     * @return string
     */
    private function montarValues(array $Parametros): string
    {
        $Campos = '(';
        foreach ($Parametros as $Parametro) {
            $Campo = trim($Parametro[0]);
            $Valor = $this->verificarValor($Parametro[2]);
            switch (true) {
                case $Valor === false:
                    break;
                case is_null($Valor):
                    $Campos .= "NULL, ";
                    break;
                default:
                    $Campos .= ":{$Campo}, ";
                    break;
            }
        }
        return " VALUES " . $this->finalizarQuery($Campos);
    }

    /**
     * Remove ',' do final da query e fecha o ultimo parentese.
     *
     * @param string $Query <p>Query a ser tratada</p>
     * @return string
     */
    private function finalizarQuery(string $Query): string
    {
        if ($Query === '(') {
            $Query = '';
        } else {
            $Query = substr($Query, 0, - 2) . ')';
        }
        return $Query;
    }
}

//PHP EOF
