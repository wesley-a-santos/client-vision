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

use Classes\Entity\Acesso;
use Classes\Entity\UnidadeCaixa;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of AcessoRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class AcessoRepository extends EntityRepository  {
    
    private $Classe;

   
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->Classe = Acesso::class;
    }
    
    
    public function armazenarAcesso(string $SessaoID, string $CodigoUsuario, string $CodigoUnidade)
    {
        if (is_null($this->verificarAcesso($SessaoID))) {
            $Acesso = new Acesso();
            $Acesso->setSessaoID($SessaoID);
            $Acesso->setCodigoUsuario($CodigoUsuario);
            $Acesso->setUnidadeCaixa($this->getUnidadeCaixa($CodigoUnidade));
            $this->getEntityManager()->persist($Acesso);
            $this->getEntityManager()->flush();
        }
    }
    
    
    private function verificarAcesso(string $SessaoID): ?Acesso
    {

        $DQL = "SELECT A FROM {$this->Classe} A WHERE (A.SessaoID = :SessaoID) AND (A.DataAcesso = :DataAcesso)";
        
        return $this->getEntityManager()->createQuery($DQL)
                ->setParameter('SessaoID', $SessaoID)
                ->setParameter('DataAcesso', new DateTimeImmutable())
                ->getOneOrNullResult();
    }
    
    private function getUnidadeCaixa(string $CodigoUnidade): UnidadeCaixa
    {
        return $this->getEntityManager()
                ->getRepository(UnidadeCaixa::class)
                ->pesquisarUnidadeCaixa($CodigoUnidade);
    }
    
    
}
