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

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of UsuarioOnline
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * @Entity(repositoryClass="Classes\Repository\UsuarioOnlineRepository")
 * @Table(name="UsuariosOnline")
 */
class UsuarioOnline {

    /**
     * @Id
     * @GeneratedValue(strategy="NONE")
     * @Column(type="string", length=30, name="SessaoID", unique=true, nullable=false)
     */
    private $SessaoID;

    /**
     * @Column(type="datetime", name="DataAcesso", unique=false, nullable=false, options={"default" : "GETDATE()"})
     */
    private $DataAcesso;

    /**
     * @Column(type="string", length=7, name="CodigoUsuario", unique=false, nullable=false, options={"fixed" : true})
     */
    private $CodigoUsuario;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct() {
        $this->DataAcesso = new DateTimeImmutable();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getSessaoID(): string {
        return $this->SessaoID;
    }

    public function getDataAcesso(): DateTimeImmutable {
        return DateTimeImmutable::createFromMutable($this->DataAcesso);
    }

    public function getCodigoUsuario(): string {
        return $this->CodigoUsuario;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setSessaoID(string $SessaoID): self {
        $this->SessaoID = $SessaoID;
        return $this;
    }

//    public function setDataAcesso(DateTimeImmutable $DataAcesso): self {
//        $this->DataAcesso = $DataAcesso;
//        return $this;
//    }

    public function setCodigoUsuario(string $CodigoUsuario): self {
        $this->CodigoUsuario = $CodigoUsuario;
        return $this;
    }

}
