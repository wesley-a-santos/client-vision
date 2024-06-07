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

namespace Classes\Helper;

/**
 * Description of Arvore
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class Hierarquia {

    /**
     * function construir
     * 
     * <p>Fontes:</p>
     * <ul>
     * <li>https://stackoverflow.com/questions/13877656/php-hierarchical-array-parents-and-childs</li>
     * <li>https://gist.github.com/ubermaniac/8834601</li>
     * <li>https://www.sitepoint.com/community/t/php-recursive-multidimensional-array-to-html-nested-code/256533</li>
     * </ul>
     * 
     * @param array $Elementos
     * @param array $Opcoes['ColunaIdPai', 'ColunaFilha', 'ColunaID'] 
     * @param int $IdPai
     * @return array
     * 
     * 
     */
    public function construir(array $Elementos, $IdPai = 0, $Opcoes = [
                'ColunaIdPai' => 'UnidadeSubordinacaoID',
                'ColunaFilha' => 'Filha',
                'ColunaID' => 'UnidadeCaixaID'])
    {
        $Hierarquia = array();
        foreach ($Elementos as $element) {
            if ($element[$Opcoes['ColunaIdPai']] == $IdPai) {
                $Filho = $this->construir($Elementos, $element[$Opcoes['ColunaID']], $Opcoes);
                if ($Filho) {
                    $element[$Opcoes['ColunaFilha']] = $Filho;
                }
                $Hierarquia[] = $element;
            }
        }
        return $Hierarquia;
    }

   
    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function somarValores(array $Elementos, $IdPai = 0, $Opcoes = [
                'ColunaIdPai' => 'UnidadeSubordinacaoID',
                'ColunaFilha' => 'Filha',
                'ColunaID' => 'UnidadeCaixaID',
                'ColunaContagem' => 'Quantidade'])
    {

        $UnidadesFilhas = $this->construir($Elementos, $IdPai, $Opcoes);

        $Hierarquia = [
            'UnidadeCaixaID' => $IdPai,
            'UnidadesFilhas' => $UnidadesFilhas,
            'Quantidade' => $this->somar($UnidadesFilhas, $Opcoes),
        ];

        return $Hierarquia;
    }

    private function somar(array $Elementos, $Opcoes = [
                'ColunaIdPai' => 'UnidadeSubordinacaoID',
                'ColunaFilha' => 'Filha',
                'ColunaID' => 'UnidadeCaixaID',
                'ColunaContagem' => 'Quantidade'])
    {
        $Quantidade = 0;

        foreach ($Elementos as $KeyPrimeiroNivel => $PrimeiroNivel) {
            foreach ($Elementos[$KeyPrimeiroNivel] as $KeySegundoNivel => $SegundoNivel) {
                if ($KeySegundoNivel === $Opcoes['ColunaContagem']) {
                    $Quantidade += $SegundoNivel;
                }
                if ((!is_null($SegundoNivel)) && ($KeySegundoNivel === $Opcoes['ColunaFilha'])) {
                    $Valor = $this->somar($SegundoNivel, $Opcoes);
                    if ($Valor) {
                        $Quantidade += $Valor;
                    }
                }
            }
        }
        return $Quantidade;
    }

}
