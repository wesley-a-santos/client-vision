<?php

/*
 * Copyright (C) 2020 Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Classes\Formatacao;

use DateTime;

/**
 * Description of FormatarValor
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class FormatarValor {

    /**
     * Altera o formato da data informada
     *
     * @param string $FormatoAtual <p>Formato atual da data, padrão PHP.</p>
     * @param string $NovoFormato <p>Formato que será retornado, padrão PHP.</p>
     * @param string $Data <p>Data a ser formatada</p>
     * @return string|null <p>Retorna a data no novo formato em caso de data válida. Retorna null para data inválida</p>
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
    public static function formatarNumeroSQL(?string $Valor): string {
        $Retorno = '0';
        if (($Valor !== null) && (trim($Valor) !== '')) {
            $Retorno = str_replace(',', '.', str_replace('.', '', $Valor));
        }
        return $Retorno;
    }

    /**
     * Formata um valor numerico no formato padrão brasileiro.
     *
     * @param int $Valor <p>Valor a ser formatado</p>
     * @param int $CasasDecimais <p>Quantidade de casas decimais a serem exibidas</p>
     * @return string|null
     */
    public static function formatarNumero(?int $Valor, int $CasasDecimais = 0): ?string {
        $Retorno = 0;
        if (($Valor !== null)) {
            $Retorno = number_format($Valor, $CasasDecimais, ',', '.');
        }
        return $Retorno;
    }

    /**
     * Limpa uma string retornando apenas os numeros
     * @param string|null $Texto <p>Texto a ser "limpo"</p>
     * @return string
     */
    public static function retornarSomenteNumeros(?string $Texto): string {
        return preg_replace('/[^0-9]/', '', $Texto);
    }

    /**
     * Formata um valor de acordo com o padrão informado.
     *
     * @param string $Valor <p>Valor a ser formatado</p>
     * @param string $Padrão <p>Padrão a ser utilizado na formatação de <b>$Valor</b>.</p>
     * @return string|null
     */
    public static function formatarValor(?string $Valor, ?string $Padrão): ?string {
        if (($Valor !== null) && ($Padrão !== null) && (trim($Padrão) !== '') && (strlen(trim($Padrão)) >= strlen(trim($Valor)))) {
            $Valor = vsprintf(
                    str_replace('0', '%s', $Padrão),
                    str_split(
                            self::completarZerosEsquerda(self::retornarSomenteNumeros($Valor), strlen(self::retornarSomenteNumeros($Padrão)))
                    )
            );
        }
        return $Valor;
    }

    /**
     * Completa um valor, com zeros a esquerda, conforme comprimento informado
     * 
     * @param string $Valor <p>Valor a ser formatado</p>
     * @param int $Comprimento <p>Comprimento do Valor a ser retornado</p>
     * @return string
     */
    public static function completarZerosEsquerda(string $Valor, int $Comprimento): string {
        $ComprimentoString = strlen(trim($Valor));
        if ($ComprimentoString < $Comprimento) {
            $Valor = str_pad(trim($Valor), ($Comprimento), "0", STR_PAD_LEFT);
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
    public static function formatarDocumento(?int $Documento, ?string $Tipo): ?string {
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

}
