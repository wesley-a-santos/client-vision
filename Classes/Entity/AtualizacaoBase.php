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
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of AtualizacaoBase
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 *
 * @Entity(repositoryClass="Classes\Repository\AtualizacaoBaseRepository")
 * @Table(name="AtualizcoesBases")
 */
class AtualizacaoBase {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="AtualizacaoBaseID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="date", name="DataAtualizacao", unique=false, nullable=false)
     */
    private $DataAtualizacao;

    /**
     * @ManyToOne(targetEntity="GrupoBase", inversedBy="AtualizcoesBases", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="GrupoBaseID", referencedColumnName="GrupoBaseID")
     */
    private $GrupoBase;

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    function getId(): int
    {
        return $this->Id;
    }

    function getDataAtualizacao(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->DataAtualizacao);
    }

    function getGrupoBase(): GrupoBase
    {
        return $this->GrupoBase;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    function setDataAtualizacao(DateTimeImmutable $DataAtualizacao): self
    {
        $this->DataAtualizacao = $DataAtualizacao;
        return $this;
    }

    function setGrupoBase(GrupoBase $GrupoBase): self
    {
        $this->GrupoBase = $GrupoBase;
        return $this;
    }

}
