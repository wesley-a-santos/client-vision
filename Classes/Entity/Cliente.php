<?php

/*
 * Copyright (C) 2020 Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Classes\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Cliente
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 * @Entity(repositoryClass="Classes\Repository\ClienteRepository")  
 * @Table(name="Clientes")
 */
class Cliente {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="ClienteID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="bigint", name="Documento", unique=true, nullable=false)
     */
    private $Documento;

    /**
     * @Column(type="string", length=100, name="Nome", unique=true, nullable=false)
     */
    private $Nome;

    /**
     * @Column(type="string", length=1, name="Tipo", unique=true, nullable=false, options={"fixed" : true})
     */
    private $Tipo;

    /**
     * @Column(type="bigint", name="InscricaoSocial", nullable=true, unique=true)
     */
    private $InscricaoSocial;

    /**
     * @ManyToMany(targetEntity="Contrato", mappedBy="Clientes")
     * @JoinTable(name="Clientes_Contratos",
     *      joinColumns={@JoinColumn(name="ClienteID", referencedColumnName="ClienteID")},
     *      inverseJoinColumns={@JoinColumn(name="ContratoID", referencedColumnName="ContratoID")}
     * )
     */
    private $Contratos;

    /**
     * @OneToMany(targetEntity="Demanda", mappedBy="Cliente", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="ClienteID", referencedColumnName="ClienteID")
     */
    private $Demandas;

    /**
     * @OneToMany(targetEntity="Informacao", mappedBy="Cliente", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="ClienteID", referencedColumnName="ClienteID")
     */
    private $Informacoes;
    
    /**
     * @Column(type="string", length=150, name="Email", unique=true, nullable=true)
     */
    private $Email;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct() {
        $this->Contratos = new ArrayCollection();
        $this->Informacoes = new ArrayCollection();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int {
        return $this->Id;
    }

    public function getDocumento(): int {
        return $this->Documento;
    }

    public function getNome(): string {
        return $this->Nome;
    }

    public function getTipo(): string {
        return $this->Tipo;
    }

    public function getInscricaoSocial(): ?int {
        return $this->InscricaoSocial;
    }

    public function getContratos(): Collection {
        return $this->Contratos;
    }

    public function getDemandas(): Collection {
        return $this->Demandas;
    }

    public function getInformacoes(): Collection {
        return $this->Informacoes;
    }
    
    public function getEmail() : ?string
    {
        return $this->Email;
    }

    
    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setDocumento(int $Documento): self {
        $this->Documento = $Documento;
        return $this;
    }

    public function setNome(string $Nome): self {
        $this->Nome = $Nome;
        return $this;
    }

    public function setTipo(string $Tipo): self {
        $this->Tipo = $Tipo;
        return $this;
    }

    public function setInscricaoSocial(?int $InscricaoSocial): self {
        $this->InscricaoSocial = $InscricaoSocial;
        return $this;
    }
    
    public function setEmail(?string $Email): self
    {
        $this->Email = $Email;
        return $this;
    }

    
    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addContrato(Contrato $Contrato): self {
        if (!$this->Contratos->contains($Contrato)) {
            $this->Contratos->add($Contrato);
            $Contrato->addCliente($this);
        }
        return $this;
    }

    public function addDemanda(Demanda $Demanda): self {
        $this->Demandas->add($Demanda);
        $Demanda->setCliente($this);
        return $this;
    }

    public function addInformacao(Informacao $Informacao): self {
        $this->Informacoes->add($Informacao);
        $Informacao->setCliente($this);
        return $this;
    }

}
