<?php

namespace Classes\BancoDeDados;

use PDO;
use PDOException;

/**
 * Executa operações de conexão e execução no banco de dados.
 *
 * @author c068442
 */
abstract class BancoDeDados
{

    /**
     * @var \PDO $Conexao
     */
    protected static $Conexao = null;

    /**
     * @var \PDOStatement $Statement
     */
    protected static $Statement;

    /**
     * <p>Conecta ao banco de dados padrão da aplicação.</p>
     *
     * @return PDO
     */
    protected static function conectarBanco(): void
    {
        // One connection to rule then all
        if (self::$Conexao === null) {
            try {
                $DSN = 'sqlsrv:Server=' . SERVIDOR . ';Database=' . BASE;
                self::$Conexao = new PDO($DSN, USUARIO, SENHA);
                self::$Conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }

    /**
     * <p>Função que monta as condições de pesquisa com base nos parametros fornecidos.</p>
     *
     * * @param array $Parametros <p>Lista de array's, contendo [Campo, Condição, Valor]</p>
     * @return string
     */
    public function montarWhere(array $Parametros): string
    {
        $Where = " WHERE (1 = 1) ";
        foreach ($Parametros as $Parametro) {
            $Campo = trim($Parametro[0]);
            $Condicao = trim($Parametro[1]);
            $Valor = $this->verificarValor($Parametro[2]);
            switch (true) {
                case $Valor === false:
                    break;
                case is_null($Valor):
                    $Where .= 'AND ' . $this->montarWhereNULL($Campo, $Condicao);
                    break;
                case (($Condicao === 'IN') || ($Condicao === 'NOT') || ($Condicao === 'NOT IN')):
                    $Where .= 'AND ' . $this->montarWhereIN($Campo, $Condicao, $Valor);
                    break;
                default:
                    $Where .= 'AND ' . "({$Campo} {$Condicao} :{$Campo}) ";
                    break;
            }
        }
        return $Where;
    }

    /**
     * <p>Monta parametros de consulta com 'IS NULL' ou 'NOT IS NULL'</p>
     *
     * @param string $Campo <p>Campo a ser pesquisado no banco de dados.</p>
     * @param string $Condicao <p>Condição de consulta. =, <>, !=, NULL, IS NULL, NOT, NOT NULL, &GT;, &LT;, &GT;= ou &LT;=.
     * @return string
     */
    protected function montarWhereNULL(string $Campo, string $Condicao): string
    {
        $Where = '';
        if ((strtoupper($Condicao) === 'NOT') || (strtoupper($Condicao) === 'NOT NULL')) {
            $Where = "(NOT({$Campo} IS NULL)) ";
        } else {
            $Where = "({$Campo} IS NULL) ";
        }
        return $Where;
    }
    
    protected function montarWhereIN(string $Campo, string $Condicao, string $Valor): string
    {
        $Where = '';
        if ((strtoupper($Condicao) === 'NOT') || (strtoupper($Condicao) === 'NOT IN')) {
            $Where = "(NOT({$Campo} IN ({$Valor}))) ";
        } else {
            $Where = "({$Campo} IN ({$Valor})) ";
        }
        return $Where;
    }

    /**
     * <p>Prepara a query para execução.</p>
     *
     * @param string $Query <p>Query SQL para consulta ou alteração de dados.</p>
     * @return void
     */
    public function setQuery(string $Query): void
    {
        try {
            self::$Statement = self::$Conexao->prepare($Query);
        } catch (PDOException $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    /**
     * <p>Fornece diretamente os parametros para uma query.</p>
     *
     * @param string $Nome <p>Nome do parâmetro fornecido.</p>
     * @param string $Valor <p>Valor do parâmetro</p>
     * @param string $Tipo <p>Tipo do parâmetro, conforme tipos do banco de dados.</p>
     * @return void
     */
    public function setParametros(string $Nome, string $Valor, string $Tipo): void
    {
        switch (true) {
            case $Valor === false:
                break;
            case strtoupper($Valor) === "NULL":
            case is_null($Valor):
                $Valor = null;
                // no break
            default:
                $Tipo = $this->selecionarTipoParametro($Tipo);
                break;
        }
        self::$Statement->bindParam(":{$Nome}", $Valor, $Tipo);
    }

    /**
     * <p>Fornece uma lista de parametros para uma query.</p>
     *
     * @param array $Parametros <p>Lista de array's, contendo [Campo, Condição, Valor]</p>
     * @return string
     */
    public function carregarParametros(array $Parametros): string
    {
        $Query = '';
        foreach ($Parametros as $Parametro) {
            $Campo = trim($Parametro[0]);
            $Condicao = trim($Parametro[1]);
            $Valor = $this->verificarValor($Parametro[2]);
            switch (true) {
                case $Valor === false:
                case strtoupper($Valor) === "NULL":
                case is_null($Valor):
                case (($Condicao === 'IN') || ($Condicao === 'NOT') || ($Condicao === 'NOT IN')):
                    break;
                default:
                    $Query .= $this->carregarParametrosValor($Campo, $Valor);
                    break;
            }
        }
        return $Query;
    }

    /**
     *  <p>Fornece os parametros para uma query.</p>
     *
     * @param string $Nome <p>Nome do parâmetro fornecido.</p>
     * @param string $Valor <p>Valor do parâmetro</p>
     * @return void
     */
    protected function carregarParametrosValor(string $Nome, string $Valor): void
    {
        if (is_numeric($Valor)) {
            $Tipo = self::selecionarTipoParametro("INT");
        } elseif (is_null($Valor)) {
            $Tipo = self::selecionarTipoParametro("NULL");
        } else {
            $Tipo = self::selecionarTipoParametro("VARCHAR");
        }
        self::setParametros($Nome, $Valor, $Tipo);
    }

    /**
     * <p>Executa a query no banco de dados.</p>
     */
    public function executar(): void
    {
        try {
//            $this->depurar();
            self::$Statement->execute();
        } catch (PDOException $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    /**
     * <p>Informa se houve retorno ou alteração das informações do banco
     *
     * @return int <p><b>0</b>: não houve retorno/alteração<br><b>1</b> ou <b>-1</b> houve retorno/alteração
     */
    public function getRetorno(): int
    {
        return self::$Statement->rowCount();
    }

    /**
     * <p>Retorna uma lista com os resultados da consulta</p>
     *
     * @return array
     */
    public function getLista(): array
    {
        return self::$Statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * <p>Retorna os dados resultantes da consulta</p>
     *
     * @return array
     */
    public function getDados(): array
    {
        return self::$Statement->fetch(PDO::FETCH_ASSOC);
        // return $this->Lista;
    }

    /**
     * <p>Finaliza a conexão ativa.</p>
     * @return void
     */
    public function desconectar(): void
    {
        self::$Conexao = null;
    }

    /**
     * <p>Verifica os parametros recebidos pela conexão aberta.</p>
     */
    private function depurar()
    {
        echo "<hr>";
        echo "<pre>";
        var_dump(self::$Statement->debugDumpParams());
        echo "</pre>";
        echo "<hr>";
        exit;
    }

    /**
     * <p>Prepara o valor fornecido para ser utilizado nas montagens de querys.</p>
     * @param type $Valor
     * @return type
     */
    protected function verificarValor($Valor)
    {
        if (($Valor === null) || (strtoupper($Valor) === "NULL")) {
            $Retorno = null;
        } elseif (($Valor !== false) &&(trim($Valor) === '')) {
            $Retorno = null;
        } elseif ($Valor === false) {
            $Retorno = false;
        } else {
            $Retorno = trim($Valor);
        }
        return $Retorno;
    }

    /**
     *  <p>Convert o tipo do parâmetro do tipo de data SQL para o tipo de data PDO.</p>
     *
     * @param string $Tipo
     * @return int
     */
    protected function selecionarTipoParametro(string $Tipo): int
    {
        switch (true) {
            case strtoupper($Tipo) === "NULL":
            case is_null($Tipo):
                $Tipo = PDO::PARAM_NULL;
                break;
            case strtoupper($Tipo) === "BOOLEAN":
                $Tipo = PDO::PARAM_BOOL;
                break;
            case strtoupper($Tipo) === "TINYINT":
            case strtoupper($Tipo) === "SMALLINT":
            case strtoupper($Tipo) === "INT":
                $Tipo = PDO::PARAM_INT;
                break;
            default:
                $Tipo = PDO::PARAM_STR;
                break;
        }
        return $Tipo;
    }

    /**
     * Prepara um array contendo as informações no formato atual de parametros da classe
     *
     * @param array $Parametros
     * @return type
     */
    public function montarParametros(array $Parametros)
    {
        foreach ($Parametros as $key => $value) {
            $Retorno[] = [$key, null, $this->verificarValor($value)];
        }
        return $Retorno;
    }
}

//PHP EOF
