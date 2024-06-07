<?php

namespace Classes\Helper;

use DateTime;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Formatação e validação de valores
 *
 * @author c068442
 */
class Variaveis {
    
    /*
     * ***********************************************************************************************************
     * * Data e Hora
     * ***********************************************************************************************************
     */

    /**
     * Altera o formato da data informada
     *
     * @param string $FormatoAtual <p>Formato atual da data, padrão PHP.</p>
     * @param string $NovoFormato <p>Formato que será retornado, padrão PHP.</p>
     * @param string $Data <p>Data a ser formatada</p>
     * @return string|null
     */
    public static function formatarData(string $FormatoAtual, string $NovoFormato, string $Data = null): ?string {
        if (($Data !== null) && trim($Data) !== '') {
            try {
                $DateTime = DateTime::createFromFormat($FormatoAtual, $Data);
                $Data = $DateTime->format($NovoFormato);
            } catch (Exception $e) {
                echo "<pre>";
                var_dump($e);
                echo "</pre>";
            }
        }
        return $Data;
    }

    /**
     * Formata um valor no formato númerico no padrão brasileiro para um valor no formato padrão SQL.
     *
     * @param string $Valor <p>Valor no padrão pt-BR (000.000,00)</p>
     * @return string <p>Formato padrão SQL (000000.00).</p>
     */
    public static function formatarValorSQL(string $Valor = null): string {
        $Retorno = 0;
        if (($Valor !== null) && (trim($Valor) !== '')) {
            $Retorno = str_replace(',', '.', str_replace('.', '', $Valor));
        }
        return $Retorno;
    }

    /**
     * Calcula a quantidade de dias entre duas datas informadas.
     *
     * @param string $FormatoDataInicio <p>Formato de <b>$DataInicio</b>, padrão PHP.</p>
     * @param string $DataInicio <p>Data inicial do período a ser calculado.</p>
     * @param string $FormatoDataFim <p>Formato de <b>$DataFim</b>, padrão PHP.</p>
     * @param string $DataFim <p>Data final do período a ser calculado.</p>
     * @return int
     */
    public static function calcularDiferencaDatas(string $FormatoDataInicio, string $DataInicio, string $FormatoDataFim, string $DataFim): ?int {
        $Dias = null;
        if ((self::validarData($FormatoDataInicio, $DataInicio)) && (self::validarData($FormatoDataFim, $DataFim))) {
            $DataInicio = DateTime::createFromFormat($FormatoDataInicio, $DataInicio);
            $DataFim = DateTime::createFromFormat($FormatoDataFim, $DataFim);
            $Diferenca = date_diff($DataInicio, $DataFim);
            if ($Diferenca->invert === 1) {
                $Dias = $Diferenca->days * - 1;
            } else {
                $Dias = $Diferenca->days;
            }
        }
        return $Dias;
    }

    /**
     * Verifica se o valor informado é uma data válida.
     *
     * @param type $Formato <p>Formato de <b>$Data</b>, padrão PHP.</p>
     * @param type $Data <p>Data a ser verificada</p>
     * @return bool <p>True para data válida, false para inválida.</p>
     */
    public static function validarData(string $Formato = 'Y-m-d H:i:s.u', string $Data = null): bool {
        $ValidarData = DateTime::createFromFormat($Formato, $Data);
        return $ValidarData && $ValidarData->format($Formato) == $Data;
    }

    /**
     * Recebe o mês em formato numérico e retorna por extenso.
     *
     * @param int $Mes <p>Mes no formato númerico, de 1 a 12.</p>
     * @return string|null
     */
    public static function mesExtenso(int $Mes = null): ?string {
        $Retorno = $Mes;
        if (($Mes !== null) && ($Mes > 0) && ($Mes < 13)) {
            $MesExtenso = [
                1 => 'Janeiro',
                2 => 'Fevereiro',
                3 => 'Marco',
                4 => 'Abril',
                5 => 'Maio',
                6 => 'Junho',
                7 => 'Julho',
                8 => 'Agosto',
                9 => 'Novembro',
                10 => 'Setembro',
                11 => 'Outubro',
                12 => 'Dezembro',
            ];
            $Retorno = $MesExtenso[$Mes];
        }
        return $Retorno;
    }

    /*
     * ***********************************************************************************************************
     * * Números
     * ***********************************************************************************************************
     */

    /**
     * Formata um valor numerico no formato padrão brasileiro.
     *
     * @param int $Valor <p>Valor a ser formatado</p>
     * @param int $CasasDecimais <p>Quantidade de casas decimais a serem exibidas</p>
     * @return string|null
     */
    public static function formatarNumero(int $Valor = null, int $CasasDecimais = 0): ?string {
        if (($Valor !== null)) {
            $Valor = number_format($Valor, $CasasDecimais, ',', '.');
        }
        return $Valor;
    }

    /**
     * Limpa o texto retornando apenas números
     *
     * @param string $Texto <p>Valor a ser limpo</p>
     * @return string | null
     */
    public static function somenteNumeros(string $Texto = null): string {
        return preg_replace('/[^0-9]/', '', $Texto);
    }

    /**
     * Formata um valor de acordo com o padrão informado.
     *
     * @param string $Valor <p>Valor a ser formatado</p>
     * @param string $Padrão <p>Padrão a ser utilizado na formatação de <b>$Valor</b>.</p>
     * @return string|null
     */
    public static function formatarValor(string $Valor = null, string $Padrão = null): ?string {
        if (($Valor !== null) && ($Padrão !== null)) {
            $tamanho_valor = strlen($Valor);
            $tamanho_mascara = strlen(self::somenteNumeros($Padrão));
            if ($tamanho_valor < $tamanho_mascara) {
                $Valor = str_pad($Valor, $tamanho_mascara, "0", STR_PAD_LEFT);
            }
            $Valor = vsprintf(str_replace('0', '%s', $Padrão), str_split($Valor));
        }
        return $Valor;
    }

    /**
     * Formata um valor para o padrão informado.
     *
     *
     * @param int $Documento <p>Valor a ser formatado.</p>
     * @param string $Tipo
     * <p><b>F</b>: Pessoa Fisica, retorna formato CPF.
     * <br><b>J</b>: Pessoa Jurídica, retorna formato CNPJ.
     * <br><b>N</b>: Número de Inscrição Social, retorna formato PIS/NIS.</p>
     *
     * @return string|null
     */
    public static function formatarDocumento(int $Documento = null, string $Tipo = null): ?string {
        if (($Documento !== null) && ($Tipo !== null)) {
            switch ($Tipo) {
                case 'F':
                    $Documento = self::formatarValor($Documento, '000.000.000-00');
                    break;
                case 'J':
                    $Documento = self::formatarValor($Documento, '00.000.000/0000-00');
                    break;
                case 'N':
                    $Documento = self::formatarValor($Documento, '000.00000.00-0');
                    break;
            }
        }
        
        return $Documento;
    }

    /**
     * Verifica se todas as tag's html estão fechadas
     * 
     * @param string $html
     * @return string
     * @link https://gist.github.com/JayWood/348752b568ecd63ae5ce Código Original
     * @link https://stackoverflow.com/questions/3810230/close-open-html-tags-in-a-string Codigo atualizado
     */
    public static function fecharTagsHtml($html) {

        preg_match_all('#<(?!meta|img|br|hr|input|link\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);

        $openedtags = $result[1];

        preg_match_all('#</([a-z]+)>#iU', $html, $result);

        $closedtags = $result[1];
        $len_opened = count($openedtags);

        if (count($closedtags) == $len_opened) {
            return $html;
        }

        $openedtags = array_reverse($openedtags);

        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }

        return $html;
    }

    /**
     * Remove as tags html, head, title e body de um valor informado.
     *
     * @param string $Texto <p>Texto a ser limpo.</p>
     * @return string
     */
    public static function limparHtml(string $Texto = null): string {
        $tags = [
            '<html>',
            '</html>',
            '<head>',
            '</head>',
            '<title>',
            '</title>',
            '<body>',
            '</body>'
        ];
        return str_replace($tags, '', $Texto);
    }

    /**
     * Remove a tag br de um valor informado.
     *
     * @param string $Texto <p>Texto a ser limpo.</p>
     * @return string
     */
    public static function limparBr(string $Texto = null): string {
        $tags = [
            '<br />',
            '<br>'
        ];
        return str_replace($tags, '', $Texto);
    }

    /**
     * Exibe o valor de uma variavel fornecida.
     *
     * @param type $Parametro <p>Variavel a ser depurada.</p>
     */
    public static function depurar($Parametro) {
        echo "<pre>";
        var_dump($Parametro);
        echo "</pre>";
        echo "<hr>";
    }

    /**
     * Remover o campo informado de um array.
     *
     * @param string $Campo <p>Campo a ser removido</p>
     * @param array $Parametros <p>array a ser alterado</p>
     * @return void
     */
    public static function removerCampoArray(string $Campo, array &$Array) {
        $ValorCampo = null;
        if (array_key_exists($Campo, $Array)) {
            $ValorCampo = $Array[$Campo];
            unset($Array[$Campo]);
        }
        return $ValorCampo;
    }

    public static function validarInputPost(string $Variavel) {
        return (filter_has_var(INPUT_POST, $Variavel)) && (trim(filter_input(INPUT_POST, $Variavel) !== ''));
    }

    public static function validarInputGet(string $Variavel) {
        return (filter_has_var(INPUT_GET, $Variavel)) && (trim(filter_input(INPUT_GET, $Variavel) !== ''));
    }

    public static function validarArray($Array = null) {
        return (!is_null($Array)) && (is_array($Array)) && (sizeof($Array) > 0);
    }

    public static function iniciaDebugStack(EntityManager $EntityManager): DebugStack {
        $DebugStack = new DebugStack();
        $EntityManager->getConfiguration()->setSQLLogger($DebugStack);
        
        return $DebugStack;
    }

    public static function exibeDebugStack(DebugStack $DebugStack): void {

        echo "<pre>";
        print_r($DebugStack);
    }

}

//PHP EOL

