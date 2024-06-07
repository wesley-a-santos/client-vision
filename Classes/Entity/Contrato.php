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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

namespace Classes\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Contrato
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 * 
 * @Entity(repositoryClass="Classes\Repository\ContratoRepository")
 * @Table(name="Contratos")
 */
class Contrato {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="ContratoID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="bigint", name="Numero", unique=true, nullable=false)
     */
    private $Numero;

    /**
     * @Column(type="date", name="DataContratacao", unique=false, nullable=false, options={"default" : "GETDATE()"})
     */
    private $DataContratacao;

    /**
     * @Column(type="decimal", scale=3, name="ValorContratado", unique=false, nullable=false, options={"default" : "0"})
     */
    private $ValorContratado;

   

    /**
     * @ManyToMany(targetEntity="Cliente", inversedBy="Contratos")
     * @JoinTable(name="Clientes_Contratos",
     *      joinColumns={@JoinColumn(name="ContratoID", referencedColumnName="ContratoID")},
     *      inverseJoinColumns={@JoinColumn(name="ClienteID", referencedColumnName="ClienteID")}
     * )
     */
    private $Clientes;

    /**
     * @ManyToOne(targetEntity="UnidadeCaixa", inversedBy="Contratos", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $UnidadeCaixa;

    /**
     * @ManyToOne(targetEntity="Produto", inversedBy="Contratos", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="ProdutoID", referencedColumnName="ProdutoID")
     */
    private $Produto;

    /**
     * @OneToMany(targetEntity="Demanda", mappedBy="Contrato", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="ContratoID", referencedColumnName="ContratoID")
     */
    private $Demandas;

    /**
     * @OneToMany(targetEntity="Informacao", mappedBy="Contrato", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="ContratoID", referencedColumnName="ContratoID")
     */
    private $Informacoes;

    /*
     * ************************************************************
     * Constructor
     * ************************************************************
     */

    public function __construct() {
        $this->Clientes = new ArrayCollection();
        $this->Demandas = new ArrayCollection();
        $this->Informacoes = new ArrayCollection();
        $this->DataContratacao = new DateTimeImmutable();
        $this->ValorContratado = 0;
        $this->ValorCreditoAtraso = 0;
        $this->ValorDividaTotal = 0;
        $this->Ativo = true;
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int {
        return $this->Id;
    }

    public function getNumero(): string {
        return $this->Numero;
    }

    public function getDataContratacao(): DateTimeImmutable {
        return $this->DataContratacao;
    }

    public function getValorContratado(): float {
        return $this->ValorContratado;
    }

  

    public function getClientes(): Collection {
        return $this->Clientes;
    }

    public function getUnidadeCaixa(): UnidadeCaixa {
        return $this->UnidadeCaixa;
    }

    public function getProduto(): ?Produto {
        return $this->Produto;
    }

    public function getDemandas(): Collection {
        return $this->Demandas;
    }
    
    public function getInformacoes(): Collection  {
        return $this->Informacoes;
    }

    
    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setNumero(int $Numero): self {
        $this->Numero = $Numero;
        return $this;
    }

    public function setDataContratacao(DateTimeImmutable $DataContratacao): self {
        $this->DataContratacao = $DataContratacao;
        return $this;
    }

    public function setValorContratado(float $ValorContratado): self {
        $this->ValorContratado = $ValorContratado;
        return $this;
    }

  

    public function setUnidadeCaixa(UnidadeCaixa $UnidadeCaixa): self {
        $this->UnidadeCaixa = $UnidadeCaixa;
        return $this;
    }

    public function setProduto(Produto $Produto): self {
        $this->Produto = $Produto;
        return $this;
    }

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */
    
        public function addCliente(Cliente $Cliente): self {
        if (!$this->Clientes->contains($Cliente)) {
            $this->Clientes->add($Cliente);
            $Cliente->addContrato($this);
        }

        return $this;
    }

    public function addDemanda(Demanda $Demanda): self {
        $this->Demandas->add($Demanda);
        $Demanda->setContrato($this);
        return $this;
    }

    public function addInformacao(Informacao $Informacao): self {
        $this->Informacoes->add($Informacao);
        $Informacao->setContrato($this);
        return $this;
    }

}
