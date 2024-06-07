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

use Classes\Entity\Funcao;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of FuncaoRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class FuncaoRepository extends EntityRepository {

    private $Funcao;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->Funcao = Funcao::class;
    }

    public function cadastrarFuncao(string $Funcao): ?Funcao {

        $Funcao = trim($Funcao);
        
        $EntityFuncao = $this->pesquisarFuncao($Funcao);

        if (is_null($EntityFuncao)) {
            $EntityFuncao = new Funcao();
            $EntityFuncao->setNome($Funcao);
            $this->getEntityManager()->persist($EntityFuncao);
            $this->getEntityManager()->flush();
        }

        return $EntityFuncao;
    }

    public function pesquisarFuncao(string $Funcao): ?Funcao {
        return $this->getEntityManager()->getRepository($this->Funcao)->findOneBy(['Nome' => $Funcao]);
    }

}
