<?php

/*
 * Copyright (C) 2020 Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
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

use Classes\Entity\Cliente;
use Classes\Entity\Contrato;
use Classes\Entity\Demanda;
use Classes\Validacao\ValidarArray;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Description of Demanda
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 */
class DemandaRepository extends EntityRepository {

    private $Demanda;
    private $Cliente;
    private $Contrato;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->Demanda = Demanda::class;
        $this->Cliente = Cliente::class;
        $this->Contrato = Contrato::class;
    }

    public function listarDemandasCliente(?int $ClienteID): ?array
    {

        if (is_null($ClienteID)) {
            return null;
        }

        $DQL = "SELECT CLI, D, STD, T, CON, P, S "
                . "FROM {$this->Cliente} CLI "
                . "LEFT JOIN CLI.Demandas D "
                . 'LEFT JOIN D.StatusDemanda STD '
                . 'LEFT JOIN D.TipoServico T '
                . "LEFT JOIN D.Contrato CON "
                . 'LEFT JOIN CON.Produto P '
                . 'LEFT JOIN P.Sistema S '
                . ' WHERE (CLI.Id = :ClienteID) '
                . ' ORDER BY D.DataRegistro DESC, D.Id DESC ';

        return ValidarArray::retornarArray($this->getEntityManager()
                                ->createQuery($DQL)
                                ->setParameter('ClienteID', $ClienteID)
                                ->getResult());
    }

    public function listarDemandasContrato(?int $ContratoID): ?array
    {

        if (is_null($ContratoID)) {
            return null;
        }

        $DQL = "SELECT CON, CLI, D, P, S, SD, T FROM {$this->Contrato} CON "
                . ' JOIN CON.Clientes CLI '
                . ' LEFT JOIN CON.Demandas D '
                . ' LEFT JOIN CON.Produto P '
                . ' LEFT JOIN P.Sistema S '
                . ' LEFT JOIN D.StatusDemanda SD '
                . ' LEFT JOIN D.TipoServico T '
                . ' WHERE (CON.Id = :ContratoID) '
                . ' ORDER BY D.DataRegistro DESC, D.Id DESC ';

//         echo  $this->getEntityManager()
//                                 ->createQuery($DQL)
//                                 ->setParameter('ContratoID', $ContratoID)
//                                 ->getSQL();
// echo "<br>" . $ContratoID;
// exit;

        return ValidarArray::retornarArray($this->getEntityManager()
                                ->createQuery($DQL)
                                ->setParameter('ContratoID', $ContratoID)
                                ->getResult());
    }

    public function pesquisarDemanda(?int $DemandaID): ?Demanda
    {
        if (!is_null($DemandaID)) {
            return $this->getEntityManager()
                            ->getRepository($this->Demanda)
                            ->find($DemandaID);
        }

        return null;
    }

    public function cadastrarDemanda(array $Dados): Demanda
    {
        $Demanda = new Demanda();

        $Demanda->setCliente($Dados['Cliente']);
        $Demanda->setContrato($Dados['Contrato']);
        $Demanda->setUsuario($Dados['Usuario']);
        $Demanda->setUnidadeCaixa($Dados['UnidadeCaixa']);
        $Demanda->setTipoServico($Dados['TipoServico']);
        $Demanda->setStatusDemanda($Dados['StatusDemanda']);
        $Demanda->setSistemaOrigem($Dados['SistemaOrigem']);
        $Demanda->setGrauSigilo($Dados['GrauSigilo']);
        $Demanda->setDetalhamento($Dados['Detalhamento']);

        $this->getEntityManager()->persist($Demanda);
        $this->getEntityManager()->flush();

        return $Demanda;
    }

    public function listarEntradas(\DateTime $Data, int $CurrentPage = 1, int $MaxResults = 100): Paginator
    {

        $dql = "SELECT D, CLI, CON, T, SO, P, S "
                . "FROM {$this->Demanda} D "
                . "JOIN D.Cliente CLI "
                . "JOIN D.Contrato CON "
                . "JOIN D.TipoServico T "
                . "JOIN D.SistemaOrigem SO "
                . "JOIN CON.Produto P "
                . "JOIN P.Sistema S "
                . "WHERE (D.DataInclusao = :Data) "
                . "ORDER BY D.Id ASC ";
                
        $query = $this->getEntityManager()->createQuery($dql)
                ->setParameter('Data', $Data)
                ->setFirstResult($MaxResults * ($CurrentPage - 1))
                ->setMaxResults($MaxResults);

        return new Paginator($query, $fetchJoinCollection = true);
    }

}
