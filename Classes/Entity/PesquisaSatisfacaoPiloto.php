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

/**
 * Description of PesquisaSatisfacao
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 * 
 * @Entity(repositoryClass="Classes\Repository\PesquisaSatisfacaoPilotoRepository")
 * @Table(name="PesquisaSatisfacaoPiloto")
 */
class PesquisaSatisfacaoPiloto {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="PesquisaID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="smallint", name="Informacoes", unique=false, nullable=false)
     */
    private $Informacoes;

    /**
     * @Column(type="smallint", name="Layout", unique=false, nullable=false)
     */
    private $Layout;

    /**
     * @Column(type="smallint", name="Desempenho", unique=false, nullable=false)
     */
    private $Desempenho;

    /**
     * @Column(type="boolean", name="Desoneracao", unique=false, nullable=false, options={"default": true})
     */
    private $Desoneracao;

    /**
     * @Column(type="text", name="Sugestoes", unique=false, nullable=true)
     */
    private $Sugestoes;

    /**
     * @ManyToOne(targetEntity="Usuario", inversedBy="PesquisasSatisfacaoPiloto", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UsuarioID", referencedColumnName="UsuarioID")
     */
    private $Usuario;

    /**
     * @ManyToOne(targetEntity="UnidadeCaixa", inversedBy="PesquisasSatisfacaoPiloto", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="UnidadeCaixaID", referencedColumnName="UnidadeCaixaID")
     */
    private $UnidadeCaixa;

    /**
     * @Column(type="datetime", name="DataResposta", unique=false, nullable=false)
     */
    private $DataResposta;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct()
    {
        $this->DataResposta = new DateTimeImmutable();
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

    public function getInformacoes(): int
    {
        return $this->Informacoes;
    }

    public function getLayout(): int
    {
        return $this->Layout;
    }

    public function getDesempenho(): int
    {
        return $this->Desempenho;
    }

    public function getDesoneracao(): int
    {
        return $this->Desoneracao;
    }

    public function getSugestoes(): ?string
    {
        return $this->Sugestoes;
    }

    public function getUsuario(): Usuario
    {
        return $this->Usuario;
    }

    public function getUnidadeCaixa(): UnidadeCaixa
    {
        return $this->UnidadeCaixa;
    }

    public function getDataResposta(): DateTimeImmutable
    {
        return $this->DataResposta;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setInformacoes(int $Informacoes): self
    {
        $this->Informacoes = $Informacoes;
        return $this;
    }

    public function setLayout(int $Layout): self
    {
        $this->Layout = $Layout;
        return $this;
    }

    public function setDesempenho(int $Desempenho): self
    {
        $this->Desempenho = $Desempenho;
        return $this;
    }

    public function setDesoneracao(int $Desoneracao): self
    {
        $this->Desoneracao = $Desoneracao;
        return $this;
    }

    public function setSugestoes(?string $Sugestoes): self
    {
        $this->Sugestoes = $Sugestoes;
        return $this;
    }

    public function setUsuario(Usuario $Usuario): self
    {
        $this->Usuario = $Usuario;
        return $this;
    }

    public function setUnidadeCaixa(UnidadeCaixa $UnidadeCaixa): self
    {
        $this->UnidadeCaixa = $UnidadeCaixa;
        return $this;
    }

    public function setDataResposta(DateTimeImmutable $DataResposta): self
    {
        $this->DataResposta = $DataResposta;
        return $this;
    }

}
