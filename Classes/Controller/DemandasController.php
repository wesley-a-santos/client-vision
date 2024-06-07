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

namespace Classes\Controller;

use Classes\Entity\Cliente;

/**
 * Description of DemandasController
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class DemandasController {

    public static function getNiveisExibicaoClientes(array $Demandas): array
    {
        foreach ($Demandas as $Cliente) {
            $PrimeiroNivel = self::getContratosCliente($Cliente);
        }
        $NumerosContratos = self::getNiveisTiposServicosClientes($Demandas, $PrimeiroNivel['DadosContratos'], $PrimeiroNivel['MascarasContratos']);
        return $NumerosContratos;
    }

    public static function getContratosCliente(Cliente $Cliente): array
    {
        $DadosContratos = [];
        $MascarasContratos = [];

        foreach ($Cliente->getDemandas() as $Demanda) {
            if (!in_array($Demanda->getContrato()->getNumero(), $DadosContratos)) {
                $DadosContratos[] = $Demanda->getContrato()->getNumero();
                if ($Demanda->getContrato()->getProduto() != null) {
                    $MascarasContratos[] = $Demanda->getContrato()->getProduto()->getSistema()->getMascara();
                }else{
                    $MascarasContratos[] = $Demanda->getContrato()->getProduto();
                }        
            }
        }

        $NumerosContratos = [
            'DadosContratos' => $DadosContratos,
            'MascarasContratos' => $MascarasContratos]
        ;

        return $NumerosContratos;
    }

    private static function getNiveisTiposServicosClientes(array $Demandas, array $NumerosContratos, array $MascarasContratos): array
    {
        $Dados = [];
        foreach ($NumerosContratos as $Key => $Contrato) {
            $TiposServicos = [];
            foreach ($Demandas as $Cliente) {
                foreach ($Cliente->getDemandas() as $Demanda) {
                    if (($Contrato === $Demanda->getContrato()->getNumero()) && (!in_array($Demanda->getTipoServico()->getTipo(), $TiposServicos))) {
                        $TiposServicos[] = $Demanda->getTipoServico()->getTipo();
                    }
                }
                $Dados[$Key] = ['Contrato' => $Contrato, 'Mascara' => $MascarasContratos[$Key], 'TiposServicos' => $TiposServicos];
            }
        }
        return $Dados;
    }

    /*     * *************************************************************************************** */

    public static function getNiveisExibicaoContratos(array $Demandas): array
    {
        $DadosContratos = [];
        $MascarasContratos = [];

        foreach ($Demandas as $Contratos) {

            if (!in_array($Contratos->getNumero(), $DadosContratos)) {
                $DadosContratos[] = $Contratos->getNumero();
                $MascarasContratos[] = $Contratos->getProduto()->getSistema()->getMascara();
            }
        }

        $NumerosContratos = self::getNiveisTiposServicosContratos($Demandas, $DadosContratos, $MascarasContratos);
        return $NumerosContratos;
    }

    private static function getNiveisTiposServicosContratos(array $Demandas, array $NumerosContratos, array $MascarasContratos): array
    {
        $Dados = [];
        foreach ($NumerosContratos as $Key => $Numero) {
            $TiposServicos = [];
            foreach ($Demandas as $Contrato) {
                foreach ($Contrato->getDemandas() as $Demanda) {
                    if (!in_array($Demanda->getTipoServico()->getTipo(), $TiposServicos)) {
                        $TiposServicos[] = $Demanda->getTipoServico()->getTipo();
                    }
                }

                $Dados[$Key] = ['Contrato' => $Numero, 'Mascara' => $MascarasContratos[$Key], 'TiposServicos' => $TiposServicos];
            }
        }
        return $Dados;
    }

}
