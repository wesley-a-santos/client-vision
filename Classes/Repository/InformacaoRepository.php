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

use Classes\Entity\Informacao;
use Classes\Entity\TipoInformacao;
use Classes\Validacao\ValidarArray;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of Informacao
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 */
class InformacaoRepository extends EntityRepository {

    private $Informacao;
    private $TipoInformacao;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->Informacao = Informacao::class;
        $this->TipoInformacao = TipoInformacao::class;
    }

    
    
    
    public function listarInformacoesCliente(int $ClienteID, int $TipoInformacaoID): ?array
    {

        $DQL = "SELECT I, T, CLI, CON, P, S FROM {$this->Informacao} I "
                . "JOIN I.TipoInformacao T "
                . "JOIN I.Cliente CLI "
                . "LEFT JOIN I.Contrato CON "
                . "LEFT JOIN CON.Produto P "
                . "LEFT JOIN P.Sistema S "
                . "WHERE (CLI.Id = :ClienteID) AND (T.Id = :TipoInformacaoID) "
                . "AND ((I.DataValidade >= :DataValidade) OR (I.Permanente = 1)) ";

        $Lista = $this->getEntityManager()
                ->createQuery($DQL)
                ->setParameter('ClienteID', $ClienteID)
                ->setParameter('TipoInformacaoID', $TipoInformacaoID)
                ->setParameter('DataValidade', new \DateTimeImmutable())
                ->getResult();
        
        return ValidarArray::retornarArray($Lista);
    }
    
    
    
    
    
    public function cadastrarInformacao(array $Informacao)
    {

        $Informacao = ValidarArray::trocarValoresVaziosPorNulos($Informacao);

        $EntityInformacao = $this->pesquisarInformacao($Informacao['InformacaoID']);

        $EntityTipoInformacao = $this->pesquisarTipoInformacao($Informacao['TipoInformacaoID']);

        $Informacao['TipoInformacao'] = $EntityTipoInformacao;

        if (is_null($EntityInformacao)) {
            return $this->cadastrar($Informacao);
        }

        return $this->atualizar($EntityInformacao, $Informacao);
    }

    public function listarInformacoesUsuario(string $CodigoUsuario): array
    {

        $DQL = "SELECT I, T, CLI, U, CON FROM {$this->Informacao} I "
                . "JOIN I.TipoInformacao T "
                . "JOIN I.Cliente CLI "
                . "JOIN I.Usuario U "
                . "LEFT JOIN I.Contrato CON "
                . "LEFT JOIN CON.Produto P "
                . "LEFT JOIN P.Sistema S "
                . "WHERE (U.CodigoUsuario = :CodigoUsuario)";

        $Lista = $this->getEntityManager()
                ->createQuery($DQL)
                ->setParameter('CodigoUsuario', $CodigoUsuario)
                ->getResult();

        return ValidarArray::retornarArray($Lista);
    }

    public function dadosInformacao(int $InformacaoID): ?Informacao
    {

        $DQL = "SELECT I, T, CLI, U, CON FROM {$this->Informacao} I "
                . "JOIN I.TipoInformacao T "
                . "JOIN I.Cliente CLI "
                . "JOIN I.Usuario U "
                . "LEFT JOIN I.Contrato CON "
                . "LEFT JOIN CON.Produto P "
                . "LEFT JOIN P.Sistema S "
                . "WHERE (I.Id = :InformacaoID)";

        return $this->getEntityManager()
                ->createQuery($DQL)
                ->setParameter('InformacaoID', $InformacaoID)
                ->getOneOrNullResult();
    }

    public function pesquisarInformacao(?int $InformacaoID): ?Informacao
    {
        if (!is_null($InformacaoID)) {
            return $this->getEntityManager()
                            ->getRepository($this->Informacao)
                            ->find($InformacaoID);
        }

        return null;
    }

    public function pesquisarTipoInformacao(?int $TipoInformacaoID): ?TipoInformacao
    {
        if (!is_null($TipoInformacaoID)) {

            return $this->getEntityManager()
                            ->getRepository($this->TipoInformacao)
                            ->find($TipoInformacaoID);
        }

        return null;
    }

    private function cadastrar(array $Informacao)
    {

        try {

            $EntityInformacao = new Informacao();

            $EntityInformacao->setDataValidade($Informacao['DataValidade'])
                    ->setCliente($Informacao['Cliente'])
                    ->setContrato($Informacao['Contrato'])
                    ->setTitulo($Informacao['Titulo'])
                    ->setDescricao($Informacao['Descricao'])
                    ->setUsuario($Informacao['Usuario'])
                    ->setUnidadeCaixa($Informacao['UnidadeCaixa'])
                    ->setTipoInformacao($Informacao['TipoInformacao'])
                    ->setPermanente($Informacao['Permanente']);

            $this->getEntityManager()->persist($EntityInformacao);
            $this->getEntityManager()->flush();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        } finally {
            
        }

        return $EntityInformacao;
    }
    
    private function atualizar(Informacao $EntityInformacao, array $Informacao)
    {

        try {

            $EntityInformacao->setDataValidade($Informacao['DataValidade'])
                    ->setCliente($Informacao['Cliente'])
                    ->setContrato($Informacao['Contrato'])
                    ->setTitulo($Informacao['Titulo'])
                    ->setDescricao($Informacao['Descricao'])
                    ->setUsuario($Informacao['Usuario'])
                    ->setUnidadeCaixa($Informacao['UnidadeCaixa'])
                    ->setTipoInformacao($Informacao['TipoInformacao'])
                    ->setPermanente($Informacao['Permanente']);

            $this->getEntityManager()->flush();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        } finally {
            
        }

        return $EntityInformacao;
    }

}
