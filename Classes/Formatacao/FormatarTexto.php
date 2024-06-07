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

/**
 * Description of FormatarTexto
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class FormatarTexto {

    /**
     * Recebe o mês em formato numérico e retorna por extenso.
     *
     * @param int $Mes <p>Mes no formato númerico, de 1 a 12.</p>
     * @return string|null
     */
    public static function mesExtenso(?int $Mes): ?string {
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
    public static function limparHtml(?string $Texto): string {
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
    public static function limparBr(?string $Texto): string {
        $tags = [
            '<br />',
            '<br>'
        ];
        return str_replace($tags, '', $Texto);
    }

}
