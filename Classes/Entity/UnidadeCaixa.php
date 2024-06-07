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
 * Description of UnidadeCaixa
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * 
 * @Entity(repositoryClass="Classes\Repository\UnidadeCaixaRepository")  
 * @Table(name="UnidadesCaixa")
 */
class UnidadeCaixa {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="UnidadeCaixaID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=4, name="Codigo", nullable=false, unique=true, options={"fixed" : true}))
     */
    private $Codigo;

    /**
     * @Column(type="smallint", name="Digito", nullable=false, unique=false)
     */
    private $Digito;

    /**
     * @Column(type="string", length=50, name="Nome", nullable=false, unique=false)
     */
    private $Nome;

    /**
     * @Column(type="string", length=8, name="Sigla", nullable=true, unique=false)
     */
    private $Sigla;

    /**
     * @Column(type="string", length=2, name="TipoUnidade", nullable=true, unique=false, options={"fixed" : true}))
     */
    private $TipoUnidade;

    /**
     * @Column(type="string", length=3, name="TipoPontoVenda", nullable=true, unique=false, options={"fixed" : true}))
     */
    private $TipoPontoVenda;

    /**
     * @Column(type="string", length=15, name="Situacao", nullable=true, unique=false)
     */
    private $Situacao;

    /**
     * @Column(type="string", length=50, name="Cidade", nullable=true, unique=false)
     */
    private $Cidade;

    /**
     * @Column(type="string", length=2, name="UF", nullable=true, unique=false, options={"fixed" : true}))
     */
    private $UF;

    /**
     * @Column(type="string", length=150, name="Email", nullable=true, unique=true)
     */
    private $Email;

    /**
     * @Column(type="date", name="DataCriacao", nullable=true, unique=false)
     */
    private $DataCriacao;

    /**
     * @Column(type="date", name="DataEncerramento", nullable=true, unique=false)
     */
    private $DataEncerramento;

    /**
     * @Column(type="smallint", name="UnidadeSubordinacaoID", nullable=true, unique=false)
     */
    private $UnidadeSubordinacaoID;

    /**
     * @OneToMany(targetEntity="Acesso", mappedBy="UnidadeCaixa", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $Acessos;

    /**
     * @OneToMany(targetEntity="Usuario", mappedBy="UnidadeCaixa", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $Usuarios;

    /**
     * @OneToMany(targetEntity="Demanda", mappedBy="UnidadeCaixa", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $Demandas;

    /**
     * @OneToMany(targetEntity="Contrato", mappedBy="UnidadeCaixa", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $Contratos;

    /**
     * @OneToMany(targetEntity="Informacao", mappedBy="UnidadeCaixa", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $Informacoes;

    /**
     * @OneToMany(targetEntity="PesquisaSatisfacaoPiloto", mappedBy="UnidadeCaixa", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $PesquisasSatisfacaoPiloto;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct()
    {
        $this->Acessos = new ArrayCollection();
        $this->Informacoes = new ArrayCollection();
        $this->PesquisasSatisfacaoPiloto = new ArrayCollection();
        $this->Acessos = new ArrayCollection();
        $this->Demandas = new ArrayCollection();
        $this->Usuarios = new ArrayCollection();
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

    public function getCodigo(): string
    {
        return $this->Codigo;
    }

    public function getDigito(): int
    {
        return $this->Digito;
    }

    public function getNome(): string
    {
        return $this->Nome;
    }

    public function getSigla(): string
    {
        return $this->Sigla;
    }

    public function getTipoUnidade(): string
    {
        return $this->TipoUnidade;
    }

    public function getTipoPontoVenda(): string
    {
        return $this->TipoPontoVenda;
    }

    public function getSituacao(): string
    {
        return $this->Situacao;
    }

    public function getCidade(): string
    {
        return $this->Cidade;
    }

    public function getUF(): string
    {
        return $this->UF;
    }

    public function getEmail(): string
    {
        return $this->Email;
    }

    public function getDataCriacao(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->DataCriacao);
    }

    public function getDataEncerramento(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->DataEncerramento);
    }

    public function getUnidadeSubordinacaoID(): string
    {
        return $this->UnidadeSubordinacaoID;
    }

    public function getAcessos(): Collection
    {
        return $this->Acessos;
    }

    public function getUsuarios(): Collection
    {
        return $this->Usuarios;
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

    public function getContratos(): Collection
    {
        return $this->Contratos;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setCodigo(string $Codigo): self
    {
        $this->Codigo = $Codigo;
        return $this;
    }

    public function setDigito(int $Digito): self
    {
        $this->Digito = $Digito;
        return $this;
    }

    public function setNome(string $Nome): self
    {
        $this->Nome = $Nome;
        return $this;
    }

    public function setSigla(string $Sigla): self
    {
        $this->Sigla = $Sigla;
        return $this;
    }

    public function setTipoUnidade(string $TipoUnidade): self
    {
        $this->TipoUnidade = $TipoUnidade;
        return $this;
    }

    public function setTipoPontoVenda(string $TipoPontoVenda): self
    {
        $this->TipoPontoVenda = $TipoPontoVenda;
        return $this;
    }

    public function setSituacao(string $Situacao): self
    {
        $this->Situacao = $Situacao;
        return $this;
    }

    public function setCidade(string $Cidade): self
    {
        $this->Cidade = $Cidade;
        return $this;
    }

    public function setUF(string $UF): self
    {
        $this->UF = $UF;
        return $this;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;
        return $this;
    }

    public function setDataCriacao(string $DataCriacao): self
    {
        $this->DataCriacao = $DataCriacao;
        return $this;
    }

    public function setDataEncerramento(DateTimeImmutable $DataEncerramento): self
    {
        $this->DataEncerramento = $DataEncerramento;
        return $this;
    }

    public function setUnidadeSubordinacaoID(DateTimeImmutable $UnidadeSubordinacaoID): self
    {
        $this->UnidadeSubordinacaoID = $UnidadeSubordinacaoID;
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
        $Demanda->setUnidadeCaixa($this);
        return $this;
    }

    public function addAcesso(Acesso $Acesso): self
    {
        $this->Acessos->add($Acesso);
        $Acesso->setUnidadeCaixa($this);
        return $this;
    }

    public function addUsuario(Usuario $Usuario): self
    {
        $this->Usuarios->add($Usuario);
        $Usuario->setUnidadeCaixa($this);
        return $this;
    }

    public function addContrato(Contrato $Contrato): self
    {
        $this->Contratos->add($Contrato);
        $Contrato->setUnidadeCaixa($this);
        return $this;
    }

    public function addInformacao(Informacao $Informacao): self
    {
        $this->Informacoes->add($Informacao);
        $Informacao->setUnidadeCaixa($this);
        return $this;
    }

    public function addPesquisaSatisfacaoPiloto(PesquisaSatisfacaoPiloto $Pesquisa): self
    {
        $this->PesquisasSatisfacaoPiloto->add($Pesquisa);
        $Pesquisa->setUnidadeCaixa($this);
        return $this;
    }

}
