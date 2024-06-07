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

use Classes\Entity\Produto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of ProdutoRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class ProdutoRepository extends EntityRepository {

    private $Produto;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->Produto = Produto::class;
    }

    public function pesquisar(int $Codigo): ?Produto
    {
        $DQL = "SELECT P, S, O FROM {$this->Produto} P "
        . "JOIN P.Sistema S "
        . "JOIN P.SegmentoOperacional O "
                . "WHERE (P.Codigo = :Codigo) ";
        
                return $this->getEntityManager()
                ->createQuery($DQL)
                ->setParameter('Codigo', $Codigo)
                ->getOneOrNullResult();

    }

}
