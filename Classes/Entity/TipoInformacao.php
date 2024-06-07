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
 * Description of TipoInformacao
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * @Entity(repositoryClass="Classes\Repository\TipoInformacaoRepository")
 * @Table(name="TiposInformacoes")
 */
class TipoInformacao {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="TipoInformacaoID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=50, name="Tipo", unique=false, nullable=false)
     */
    private $Tipo;

    /**
     * @OneToMany(targetEntity="Informacao", mappedBy="TipoInformacao", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="TipoInformacaoID", referencedColumnName="TipoInformacaoID")
     */
    private $Informacoes;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct() {
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

    public function getTipo(): string {
        return $this->Tipo;
    }

    public function getInformacoes(): Collection {
        return $this->Informacoes;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setTipo(string $Tipo): self {
        $this->Tipo = $Tipo;
        return $this;
    }

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addInformacao(Informacao $Informacao): self {
        $this->Informacoes->add($Informacao);
        $Informacao->setTipoInformacao($this);
        return $this;
    }

}
