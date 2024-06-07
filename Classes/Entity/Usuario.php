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
 * Description of Usuario
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 * @Entity(repositoryClass="Classes\Repository\UsuarioRepository")
 * @Table(name="Usuarios")
 */
class Usuario {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="UsuarioID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=7, name="CodigoUsuario", unique=true, nullable=false, options={"fixed" : true})
     */
    private $CodigoUsuario;

    /**
     * @Column(type="string", length=100, name="Nome", unique=false, nullable=false)
     */
    private $Nome;

    /**
     * @Column(type="string", length=150, name="Email", unique=false, nullable=false)
     */
    private $Email;

    /**
     * @ManyToOne(targetEntity="Funcao", inversedBy="Usuarios", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="FuncaoID", referencedColumnName="FuncaoID")
     */
    private $Funcao;

    /**
     * @ManyToOne(targetEntity="UnidadeCaixa", inversedBy="Usuarios", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $UnidadeCaixa;

    /**
     * @OneToMany(targetEntity="Demanda", mappedBy="Usuario", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UsuarioID", referencedColumnName="UsuarioID")
     */
    private $Demandas;

    /**
     * @OneToMany(targetEntity="Informacao", mappedBy="Usuario", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UsuarioID", referencedColumnName="UsuarioID")
     */
    private $Informacoes;

    /**
     * @OneToMany(targetEntity="PesquisaSatisfacaoPiloto", mappedBy="Usuario", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UsuarioID", referencedColumnName="UsuarioID")
     */
    private $PesquisasSatisfacaoPiloto;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct()
    {
        $this->Demandas = new ArrayCollection();
        $this->Informacoes = new ArrayCollection();
        $this->PesquisasSatisfacaoPiloto = new ArrayCollection();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    public function getId(): int
    {
        return $this->Id;
    }

    public function getCodigoUsuario(): string
    {
        return $this->CodigoUsuario;
    }

    public function getNome(): string
    {
        return $this->Nome;
    }

    public function getEmail(): string
    {
        return $this->Email;
    }

    public function getFuncao(): Funcao
    {
        return $this->Funcao;
    }

    public function getUnidadeCaixa(): UnidadeCaixa
    {
        return $this->UnidadeCaixa;
    }

    public function getDemandas(): Collection
    {
        return $this->Demandas;
    }

    public function getInformacoes(): Collection
    {
        return $this->Informacoes;
    }

    public function getPesquisasSatisfacaoPiloto(): ?Collection
    {
        return $this->PesquisasSatisfacaoPiloto;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setCodigoUsuario(string $CodigoUsuario): self
    {
        $this->CodigoUsuario = $CodigoUsuario;
        return $this;
    }

    public function setNome(string $Nome): self
    {
        $this->Nome = $Nome;
        return $this;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;
        return $this;
    }

    public function setFuncao(Funcao $Funcao): self
    {
        $this->Funcao = $Funcao;
        return $this;
    }

    public function setUnidadeCaixa(UnidadeCaixa $UnidadeCaixa): self
    {
        $this->UnidadeCaixa = $UnidadeCaixa;
        return $this;
    }

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addDemanda(Demanda $Demanda): self
    {
        $this->Demandas->add($Demanda);
        $Demanda->setUsuario($this);
        return $this;
    }

    public function addInformacao(Informacao $Informacao): self
    {
        $this->Informacoes->add($Informacao);
        $Informacao->setUsuario($this);
        return $this;
    }

    public function addPesquisaSatisfacaoPiloto(PesquisaSatisfacaoPiloto $Pesquisa): self
    {
        $this->PesquisasSatisfacaoPiloto->add($Pesquisa);
        $Pesquisa->setUsuario($this);
        return $this;
    }

}
