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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Produto
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * 
 * @Entity(repositoryClass="Classes\Repository\ProdutoRepository") 
 * @Table(name="Produtos")
 */
class Produto {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="ProdutoID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="smallint", name="Codigo", unique=true, nullable=false)
     */
    private $Codigo;

    /**
     * @Column(type="string", length=75, name="Nome", unique=false, nullable=false)
     */
    private $Nome;

    /**
     * @ManyToOne(targetEntity="Sistema", inversedBy="Produtos", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="SistemaID", referencedColumnName="SistemaID")
     */
    private $Sistema;

    /**
     * @ManyToOne(targetEntity="SegmentoOperacional", inversedBy="Produtos", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="SegmentoOperacionalID", referencedColumnName="SegmentoOperacionalID")
     */
    private $SegmentoOperacional;

    /**
     * @OneToMany(targetEntity="Contrato", mappedBy="Produto", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="ProdutoID", referencedColumnName="ProdutoID")
     */
    private $Contratos;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct() {
        $this->Contratos = new ArrayCollection();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int {
        return $this->Id;
    }

    public function getCodigo(): int {
        return $this->Codigo;
    }

    public function getNome(): string {
        return $this->Nome;
    }

    public function getSistema(): Sistema {
        return $this->Sistema;
    }

    public function getSegmentoOperacional(): SegmentoOperacional {
        return $this->SegmentoOperacional;
    }

    public function getContratos(): Collection {
        return $this->Contratos;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setCodigo(int $Codigo): self {
        $this->Codigo = $Codigo;
        return $this;
    }

    public function setNome(string $Nome): self {
        $this->Nome = $Nome;
        return $this;
    }

    public function setSistema(Sistema $Sistema): self {
        $this->Sistema = $Sistema;
        return $this;
    }

    public function setSegmentoOperacional(SegmentoOperacional $SegmentoOperacional): self {
        $this->SegmentoOperacional = $SegmentoOperacional;
        return $this;
    }

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addContrato(Contrato $Contrato): self {
        $this->Contratos->add($Contrato);
        $Contrato->setProduto($this);
        return $this;
    }

}
