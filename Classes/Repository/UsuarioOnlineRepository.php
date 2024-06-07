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

use Classes\Entity\UsuarioOnline;
use Classes\Helper\Sessao;
use Classes\LDAP\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of UsuarioOnlineRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class UsuarioOnlineRepository extends EntityRepository {

    private $Classe;
    private $CodigoUsuario;
    private $CodigoUnidade;
    private $SessaoID;
    private $Usuario;
    private $Sessao;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->Classe = UsuarioOnline::class;
        $this->pegarDadosUsuario();
        $this->pegarDadosSessao();
    }

    private function verificarRegistroSessao(): ?UsuarioOnline
    {
        return $this->getEntityManager()->getRepository($this->Classe)->find($this->SessaoID);
    }

    private function excluirSessoesAntigas(): void
    {
        $this->getEntityManager()
                ->getConnection()
                ->executeUpdate('DELETE FROM UsuariosOnline WHERE (DataAcesso < DATEADD(mi, -10, GETDATE()))');
    }

    private function armazenarUsuariosOnline()
    {
        if (is_null($this->verificarRegistroSessao())) {
            $UsuarioOnline = new UsuarioOnline();
            $UsuarioOnline->setSessaoID($this->SessaoID);
            $UsuarioOnline->setCodigoUsuario($this->CodigoUsuario);
            $this->getEntityManager()->persist($UsuarioOnline);
            $this->getEntityManager()->flush();
        }
        
        return $this->getEntityManager()
                ->getRepository(\Classes\Entity\Acesso::class)
                ->armazenarAcesso($this->SessaoID, $this->CodigoUsuario, $this->CodigoUnidade);
        
    }

    private function pegarDadosUsuario(): void
    {
        if (is_null($this->Usuario)) {
            $this->Usuario = new Usuario();
            $this->CodigoUsuario = $this->Usuario->getCodigoUsuario();
            $this->CodigoUnidade = $this->Usuario->getCodigoUnidade();
        }
    }

    private function pegarDadosSessao(): void
    {
        if (is_null($this->Sessao)) {
            $this->Sessao = Sessao::getInstancia();
            $this->SessaoID = $this->Sessao->getSessaoID();
        }
    }

    public function getQuantidadeUsuariosOnline(): int
    {


        $this->excluirSessoesAntigas();
        $this->armazenarUsuariosOnline();
        return $this->getEntityManager()->createQuery("SELECT COUNT(U) FROM {$this->Classe} U")->getSingleScalarResult();
    }

    public function getUsuario(): Usuario
    {
        $this->pegarDadosUsuario();
        return $this->Usuario;
    }

}
