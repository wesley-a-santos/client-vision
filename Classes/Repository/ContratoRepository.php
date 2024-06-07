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

namespace Classes\Repository;

use Classes\Entity\Contrato;
use Classes\Entity\Produto;
use Classes\Formatacao\FormatarValor;
use Classes\Validacao\ValidarArray;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Exception;

/**
 * Description of ContratoRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class ContratoRepository extends EntityRepository {

    private $Contrato;
    private $Produto;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->Contrato = Contrato::class;
        $this->Produto = Produto::class;
    }

    public function cadastrarContrato(array $Contrato): ?Contrato
    {

        $Contrato['Numero'] = FormatarValor::retornarSomenteNumeros($Contrato['Numero']);
        
        $Contrato = ValidarArray::trocarValoresVaziosPorNulos($Contrato);
        
        $EntityContrato = $this->pesquisarContrato($Contrato['Numero']);

        if ((is_null($EntityContrato) && (!is_null($Contrato['Numero'])))) {

            $EntityProduto = $this->pesquisarProduto($Contrato['Codigo']);
            $EntityContrato = new Contrato();

            try {
                $EntityContrato->setNumero($Contrato['Numero'])
                        ->setProduto($EntityProduto)
                        ->setUnidadeCaixa($Contrato['UnidadeCaixa']);

                $this->getEntityManager()->persist($EntityContrato);
                $this->getEntityManager()->flush();
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        return $EntityContrato;
    }

    public function pesquisarContrato(?int $Numero): ?Contrato
    {
        return $this->getEntityManager()
                        ->getRepository($this->Contrato)
                        ->findOneBy(['Numero' => $Numero]);
    }

    public function pesquisarProduto(?int $Codigo): ?Produto
    {
        return $this->getEntityManager()
                        ->getRepository($this->Produto)
                        ->findOneBy(['Codigo' => $Codigo]);
    }

}
