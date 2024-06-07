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

namespace Classes\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Demanda
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * 
 * @Entity(repositoryClass="Classes\Repository\DemandaRepository")
 * @Table(name="Demandas")
 * @HasLifecycleCallbacks
 */
class Demanda {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="DemandaID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @ManyToOne(targetEntity="Cliente", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="ClienteID", referencedColumnName="ClienteID")
     */
    private $Cliente;

    /**
     * @ManyToOne(targetEntity="Contrato", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="ContratoID", referencedColumnName="ContratoID")
     */
    private $Contrato;

    /**
     * @ManyToOne(targetEntity="Usuario", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UsuarioID", referencedColumnName="UsuarioID")
     */
    private $Usuario;

    /**
     * @ManyToOne(targetEntity="UnidadeCaixa", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $UnidadeCaixa;

    /**
     * @ManyToOne(targetEntity="TipoServico", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="TipoServicoID", referencedColumnName="TipoServicoID")
     */
    private $TipoServico;

    /**
     * @Column(type="date", name="DataRegistro", unique=false, nullable=false, options={"default" : "GETDATE()"})
     */
    private $DataRegistro;
    
    /**
     * @Column(type="date", name="DataInclusao", unique=false, nullable=false, options={"default" : "GETDATE()"})
     */
    private $DataInclusao;

    /**
     * @ManyToOne(targetEntity="StatusDemanda", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="StatusDemandaID", referencedColumnName="StatusDemandaID")
     */
    private $StatusDemanda;

    /**
     * @ManyToOne(targetEntity="SistemaOrigem", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="SistemaOrigemID", referencedColumnName="SistemaOrigemID")
     */
    private $SistemaOrigem;

    /**
     * @ManyToOne(targetEntity="GrauSigilo", inversedBy="Demandas", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="GrauSigiloID", referencedColumnName="GrauSigiloID")
     */
    private $GrauSigilo;
    
    
    /**
     *
     * @Column(type="text", unique=false, nullable=false)
     */
    private $Detalhamento;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct() {
        $this->DataRegistro = new DateTimeImmutable();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int {
        return $this->Id;
    }

    public function getCliente(): Cliente {
        return $this->Cliente;
    }

    public function getContrato(): Contrato {
        return $this->Contrato;
    }

    public function getUsuario(): Usuario {
        return $this->Usuario;
    }

    public function getUnidadeCaixa(): UnidadeCaixa {
        return $this->UnidadeCaixa;
    }

    public function getTipoServico(): TipoServico {
        return $this->TipoServico;
    }

    public function getDataRegistro(): DateTimeImmutable {
        return DateTimeImmutable::createFromMutable($this->DataRegistro);
    }
    
    public function getDataInclusao(): DateTimeImmutable {
        return DateTimeImmutable::createFromMutable($this->DataInclusao);
    }

    public function getStatusDemanda(): StatusDemanda {
        return $this->StatusDemanda;
    }

    public function getSistemaOrigem(): SistemaOrigem {
        return $this->SistemaOrigem;
    }

    public function getGrauSigilo(): GrauSigilo {
        return $this->GrauSigilo;
    }
    
    public function getDetalhamento(): string {
        return $this->Detalhamento;
    }

    
    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setCliente(Cliente $Cliente): self {
        $this->Cliente = $Cliente;
        return $this;
    }

    public function setContrato(Contrato $Contrato): self {
        $this->Contrato = $Contrato;
        return $this;
    }

    public function setUsuario(Usuario $Usuario): self {
        $this->Usuario = $Usuario;
        return $this;
    }

    public function setUnidadeCaixa(UnidadeCaixa $UnidadeCaixa): self {
        $this->UnidadeCaixa = $UnidadeCaixa;
        return $this;
    }

    public function setTipoServico(TipoServico $TipoServico): self {
        $this->TipoServico = $TipoServico;
        return $this;
    }

    public function setDataRegistro(DateTimeImmutable $DataRegistro): self {
        $this->DataRegistro = $DataRegistro;
        return $this;
    }

    public function setStatusDemanda(StatusDemanda $StatusDemanda): self {
        $this->StatusDemanda = $StatusDemanda;
        return $this;
    }

    public function setSistemaOrigem(SistemaOrigem $SistemaOrigem): self {
        $this->SistemaOrigem = $SistemaOrigem;
        return $this;
    }

    public function setGrauSigilo(GrauSigilo $GrauSigilo): self {
        $this->GrauSigilo = $GrauSigilo;
        return $this;
    }
    
    public function setDetalhamento(string $Detalhamento): self {
        $this->Detalhamento = $Detalhamento;
        return $this;
    }
    
    
    /**
     * @PrePersist
     */
    
    public function onPrePersist(): void
    {
        $this->DataInclusao = new \DateTimeImmutable();
    }



}
