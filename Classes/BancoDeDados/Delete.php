<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\BancoDeDados;

/**
 * Executa operações de consulta no banco de dados.
 *
 * @author c068442
 */
class Delete extends BancoDeDados
{

    /**
     * Inicia a conexão com o banco de dados.
     */
    public function __construct()
    {
        self::conectarBanco();
    }
}

//PHP EOF
