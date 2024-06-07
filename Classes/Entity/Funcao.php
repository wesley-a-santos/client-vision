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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Funcao
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 * 
 * @Entity(repositoryClass="Classes\Repository\FuncaoRepository")
 * @Table(name="Funcoes")
 */
class Funcao {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="FuncaoID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=50, name="Nome", unique=true, nullable=false)
     */
    private $Nome;

    /**
     * @OneToMany(targetEntity="Usuario", mappedBy="Funcao", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="FuncaoID", referencedColumnName="FuncaoID")
     */
    private $Usuarios;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct() {
        $this->Usuarios = new ArrayCollection();
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

    public function getUsuarios(): Collection {
        return $this->Usuarios;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setNome(string $Nome): self {
        $this->Nome = $Nome;
        return $this;
    }

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addUsuario(Usuario $Usuario): self {
        $this->Usuarios->add($Usuario);
        $Usuario->setFuncao($this);
        return $this;
    }

}
