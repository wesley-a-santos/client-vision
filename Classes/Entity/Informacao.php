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

namespace Classes\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Informacao
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 * 
 * @Entity(repositoryClass="Classes\Repository\InformacaoRepository")
 * @Table(name="Informacoes")
 * @HasLifecycleCallbacks
 */
class Informacao {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="InformacaoID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="date", name="DataInclusao", unique=false, nullable=false)
     */
    private $DataInclusao;

    /**
     * @Column(type="date", name="DataValidade", unique=false, nullable=true)
     */
    private $DataValidade;

    /**
     * @Column(type="date", name="DataAlteracao", unique=false, nullable=false)
     */
    private $DataAlteracao;

    /**
     * @ManyToOne(targetEntity="Cliente", inversedBy="Informacoes", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="ClienteID", referencedColumnName="ClienteID")
     */
    private $Cliente;

    /**
     * @Column(type="text", name="Titulo", unique=false, nullable=false)
     */
    private $Titulo;

    /**
     * @Column(type="text", name="Descricao", unique=false, nullable=false)
     */
    private $Descricao;

    /**
     * @ManyToOne(targetEntity="Usuario", inversedBy="Informacoes", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UsuarioID", referencedColumnName="UsuarioID")
     */
    private $Usuario;

    /**
     * @ManyToOne(targetEntity="UnidadeCaixa", inversedBy="Informacoes", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $UnidadeCaixa;

    /**
     * @ManyToOne(targetEntity="TipoInformacao", inversedBy="Informacoes", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="TipoInformacaoID", referencedColumnName="TipoInformacaoID")
     */
    private $TipoInformacao;

    /**
     * @Column(type="boolean", name="Permanente", unique=false, nullable=false, options={"default" : "1"})
     */
    private $Permanente = false;

    /**
     * @ManyToOne(targetEntity="Contrato", inversedBy="Informacoes", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="ContratoID", referencedColumnName="ContratoID")
     */
    private $Contrato;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct()
    {
        
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int
    {
        return $this->Id;
    }

    public function getDataInclusao(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->DataInclusao);
    }

    public function getDataValidade(): ?DateTimeImmutable
    {
        if (!is_null($this->DataValidade)) {
            return DateTimeImmutable::createFromMutable($this->DataValidade);
        }
        return null;
    }

    public function getDataAlteracao(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->DataAlteracao);
    }

    public function getCliente(): Cliente
    {
        return $this->Cliente;
    }

    public function getTitulo(): string
    {
        return $this->Titulo;
    }

    public function getDescricao(): string
    {
        return $this->Descricao;
    }

    public function getUsuario(): Usuario
    {
        return $this->Usuario;
    }

    public function getUnidadeCaixa(): UnidadeCaixa
    {
        return $this->UnidadeCaixa;
    }

    public function getTipoInformacao(): TipoInformacao
    {
        return $this->TipoInformacao;
    }

    public function getPermanente(): bool
    {
        return $this->Permanente;
    }

    public function getContrato()
    {
        return $this->Contrato;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setDataValidade(?DateTimeImmutable $DataValidade): self
    {
        $this->DataValidade = $DataValidade;
        return $this;
    }

    public function setCliente(Cliente $Cliente): self
    {
        $this->Cliente = $Cliente;
        return $this;
    }

    public function setTitulo(string $Titulo): self
    {
        $this->Titulo = $Titulo;
        return $this;
    }

    public function setDescricao(string $Descricao): self
    {
        $this->Descricao = $Descricao;
        return $this;
    }

    public function setUsuario(Usuario $Usuario): self
    {
        $this->Usuario = $Usuario;
        return $this;
    }

    public function setUnidadeCaixa(UnidadeCaixa $UnidadeCaixa): self
    {
        $this->UnidadeCaixa = $UnidadeCaixa;
        return $this;
    }

    public function setTipoInformacao(TipoInformacao $TipoInformacao): self
    {
        $this->TipoInformacao = $TipoInformacao;
        return $this;
    }

    public function setPermanente(bool $Permanente): self
    {
        $this->Permanente = $Permanente;
        return $this;
    }

    public function setContrato($Contrato)
    {
        $this->Contrato = $Contrato;
        return $this;
    }

    /*
     * ************************************************************
     * Trigger
     * ************************************************************
     */

    /**
     * @PrePersist
     */
    public function onPrePersist()
    {
        $this->DataInclusao = new DateTimeImmutable();
    }

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function onPreUpdate()
    {
        $this->DataAlteracao = new DateTimeImmutable();
    }

}
