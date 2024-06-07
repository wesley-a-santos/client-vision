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
 * Description of Sistema
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * 
 * @Entity
 * @Table(name="Sistemas")
 */
class Sistema {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="SistemaID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=50, name="Nome", unique=false, nullable=false)
     */
    private $Nome;

    /**
     * @Column(type="string", length=50, name="Mascara", unique=false, nullable=false)
     */
    private $Mascara;

    /**
     * @OneToMany(targetEntity="Produto", mappedBy="Sistema", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="SistemaID", referencedColumnName="SistemaID")
     */
    private $Produtos;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct() {
        $this->Produtos = new ArrayCollection();
        $this->Mascara = '0#';
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int {
        return $this->Id;
    }

    public function getNome(): string {
        return $this->Nome;
    }

    public function getMascara(): ?string {
        return $this->Mascara;
    }

    public function getProdutos(): Collection {
        return $this->Produtos;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setSistema(string $Sistema): self {
        $this->Sistema = $Sistema;
        return $this;
    }

    public function setMascara(?string $Mascara): self {
        $this->Mascara = $Mascara;
        return $this;
    }

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addProduto(Produto $Produto): self {
        $this->Produtos->add($Produto);
        $Produto->setSistema($this);
        return $this;
    }

}
