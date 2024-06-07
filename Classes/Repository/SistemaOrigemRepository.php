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

use Classes\Entity\SistemaOrigem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of SistemaOrigemRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class SistemaOrigemRepository extends EntityRepository {

    private $SistemaOrigem;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->SistemaOrigem = SistemaOrigem::class;
    }

    public function cadastrarSistemaOrigem(string $SistemaOrigem): ?SistemaOrigem {

        $SistemaOrigem = trim($SistemaOrigem);
        
        $EntitySistemaOrigem = $this->pesquisarSistemaOrigem($SistemaOrigem);

        if (is_null($EntitySistemaOrigem)) {
            $EntitySistemaOrigem = new SistemaOrigem();
            $EntitySistemaOrigem->setSistema($SistemaOrigem);
            $this->getEntityManager()->persist($EntitySistemaOrigem);
            $this->getEntityManager()->flush();
        }

        return $EntitySistemaOrigem;
    }

    public function pesquisarSistemaOrigem(string $SistemaOrigem): ?SistemaOrigem {
        return $this->getEntityManager()->getRepository($this->SistemaOrigem)->findOneBy(['Sistema' => $SistemaOrigem]);
    }

}
