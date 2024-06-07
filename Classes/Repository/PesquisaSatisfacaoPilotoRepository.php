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


use Classes\Entity\PesquisaSatisfacaoPiloto;
use Classes\Entity\UnidadeCaixa;
use Classes\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of AcessoRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class PesquisaSatisfacaoPilotoRepository extends EntityRepository  {
    
    private $Classe;

   
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->Classe = PesquisaSatisfacaoPiloto::class;
    }
    
    
    public function gravarPesquisa(array $Dados): PesquisaSatisfacaoPiloto
    {

        $Pesquisa = new PesquisaSatisfacaoPiloto();
        
        $Pesquisa->setUsuario($Dados['Usuario']);
        $Pesquisa->setUnidadeCaixa($Dados['UnidadeCaixa']);
        $Pesquisa->setDesempenho($Dados['Desempenho']);
        $Pesquisa->setDesoneracao($Dados['Desoneracao']);
        $Pesquisa->setInformacoes($Dados['Informacoes']);
        $Pesquisa->setLayout($Dados['Layout']);
        $Pesquisa->setSugestoes($Dados['Sugestoes']);
        $this->getEntityManager()->persist($Pesquisa);
        $this->getEntityManager()->flush();
        
        return $Pesquisa;

    }
    
    public function listar(\DateTimeImmutable $DataInicio, \DateTimeImmutable $DataFim) : ?array
    {
        
        $DQL = "SELECT P, U, C FROM {$this->Classe} P "
                . " JOIN P.Usuario U "
                . "JOIN U.UnidadeCaixa C "
                . "WHERE P.DataResposta BETWEEN :DataInicio AND :DataFim"
                . "";
        
        return $this->getEntityManager()
                ->createQuery($DQL)
                ->setParameter('DataInicio', $DataInicio)
                ->setParameter('DataFim', $DataFim)
                ->getArrayResult();
    }

    private function getUnidadeCaixa(string $CodigoUnidade): UnidadeCaixa
    {
        return $this->getEntityManager()
                ->getRepository(UnidadeCaixa::class)
                ->pesquisarUnidadeCaixa($CodigoUnidade);
    }
    private function getUsuario(string $CodigoUsuario): Usuario
    {
        return $this->getEntityManager()
                ->getRepository(Usuario::class)
                ->pesquisarUsuario($CodigoUsuario);
    }
    
    
}
