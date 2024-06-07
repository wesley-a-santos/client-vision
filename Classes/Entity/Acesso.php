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
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Description of Acesso
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 * @Entity(repositoryClass="Classes\Repository\AcessoRepository")
 * @Table(name="Acessos", uniqueConstraints={@UniqueConstraint(name="IDX_Acessos_SessaoID_DataAcesso", columns={"SessaoID", "DataAcesso"})})
 */
class Acesso {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="AcessoID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=7, name="CodigoUsuario", unique=false, nullable=false, options={"fixed" : true})
     */
    private $CodigoUsuario;

    /**
     *
     * @Column(type="string", length=30, name="SessaoID", unique=false, nullable=false)
     */
    private $SessaoID;

    /**
     *
     * @ManyToOne(targetEntity="UnidadeCaixa", inversedBy="Acessos", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $UnidadeCaixa;

    /**
     * @Column(type="date", name="DataAcesso", unique=false, nullable=false)
     */
    private $DataAcesso;
    
    /**
     * @Column(type="time", name="HoraAcesso", unique=false, nullable=false)
     */
    private $HoraAcesso;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct() {
        $this->DataAcesso = new DateTimeImmutable();
        $this->HoraAcesso = new DateTimeImmutable();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int {
        return $this->Id;
    }

    public function getCodigoUsuario(): string {
        return $this->CodigoUsuario;
    }

    public function getSessaoID(): string {
        return $this->SessaoID;
    }

    public function getUnidadeCaixa(): UnidadeCaixa {
        return $this->UnidadeCaixa;
    }

    public function getDataAcesso(): DateTimeImmutable {
        return $this->DataAcesso;
    }

    public function getHoraAcesso(): DateTimeImmutable
    {
        return $this->HoraAcesso;
    }

        
    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setCodigoUsuario(string $CodigoUsuario): self {
        $this->CodigoUsuario = $CodigoUsuario;
        return $this;
    }

    public function setSessaoID(string $SessaoID): self {
        $this->SessaoID = $SessaoID;
        return $this;
    }

    public function setUnidadeCaixa(UnidadeCaixa $UnidadeCaixa): self {
        $this->UnidadeCaixa = $UnidadeCaixa;
        return $this;
    }

}
