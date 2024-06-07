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


use Classes\Entity\UnidadeCaixa;
use Classes\Formatacao\FormatarValor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\Mapping\ClassMetadata;

/**
 * Description of UnidadeCaixaRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class UnidadeCaixaRepository extends EntityRepository {

    private $UnidadeCaixa;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->UnidadeCaixa = UnidadeCaixa::class;
    }

    public function cadastrarUnidadeCaixa(array $Unidade): ?UnidadeCaixa
    {
        $Unidade['Codigo'] = $this->formatarCodigo($Unidade['Codigo']);

    
        
        $EntityUnidade = $this->pesquisarUnidadeCaixa($Unidade['Codigo']);

        if (is_null($EntityUnidade)) {
            $EntityUnidade = new UnidadeCaixa();

            $EntityUnidade->setCodigo($Unidade['Codigo'])
                    ->setDigito($Unidade['Digito'])
                    ->setNome($Unidade['Nome'])
                    ->setSigla($Unidade['Sigla']);
            
            $this->getEntityManager()->persist($EntityUnidade);
            $this->getEntityManager()->flush();
        }

        return $EntityUnidade;
    }

    public function pesquisarUnidadeCaixa(string $Codigo): ?UnidadeCaixa
    {
        
         
        
        return $this->getEntityManager()
                        ->getRepository($this->UnidadeCaixa)
                        ->findOneBy(['Codigo' => $this->formatarCodigo($Codigo)]);
    }

    private function formatarCodigo(string $Codigo): string
    {
        return FormatarValor::completarZerosEsquerda($Codigo, 4);
    }

}
