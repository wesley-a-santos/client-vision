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
 * Description of StatusDemanda
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * @Entity(repositoryClass="Classes\Repository\StatusDemandaRepository")
 * @Table(name="StatusDemandas")
 */
class StatusDemanda {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="StatusDemandaID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=50, name="Status", unique=true, nullable=false)
     */
    private $Status;


    /**
     * @OneToMany(targetEntity="Demanda", mappedBy="StatusDemanda", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="StatusDemandaID", referencedColumnName="StatusDemandaID")
     */
    private $Demandas;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct() {
        $this->Demandas = new ArrayCollection();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int {
        return $this->Id;
    }

    public function getStatus(): string {
        return $this->Status;
    }

    public function getDemandas(): Collection {
        return $this->Demandas;
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function setStatus(string $Status): self {
        $this->Status = $Status;
        return $this;
    }


    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addDemanda(Demanda $Demanda): self {
        $this->Demandas->add($Demanda);
        $Demanda->setStatusDemanda($this);
        return $this;
    }

}
