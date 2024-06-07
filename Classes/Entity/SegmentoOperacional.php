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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of SegmentoOperacional
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * 
 * @Entity  
 * @Table(name="SegmentosOperacionais")
 */
class SegmentoOperacional {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="SegmentoOperacionalID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="smallint", name="Codigo", unique=true, nullable=false)
     */
    private $Codigo;

    /**
     * @Column(type="string", length=50, name="Nome", unique=true, nullable=false)
     */
    private $Nome;

    /**
     * @OneToMany(targetEntity="Produto", mappedBy="SegmentoOperacional", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="SegmentoOperacionalID", referencedColumnName="SegmentoOperacionalID")
     */
    private $Produtos;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct() {
        $this->Produtos = new ArrayCollection();
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

    public function getProdutos(): Collection {
        return $this->Produtos;
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

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addProduto(Produto $Produto): self {
        $this->Produtos->add($Produto);
        $Produto->setSegmentoOperacional($this);
        return $this;
    }

}
