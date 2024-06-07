<?php

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
 * Description of GrupoBase
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 *
 * @Entity(repositoryClass="Classes\Repository\GrupoBaseRepository")
 * @Table(name="GruposBases")
 */
class GrupoBase {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="GrupoBaseID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=75, name="Grupo", unique=false, nullable=false)
     */
    private $Grupo;

    /**
     * @OneToMany(targetEntity="AtualizacaoBase", mappedBy="GrupoBase", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="GrupoBaseID", referencedColumnName="GrupoBaseID")
     */
    private $AtualizcoesBases;
    
        /**
     * @OneToMany(targetEntity="TipoServico", mappedBy="GrupoBase", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="GrupoBaseID", referencedColumnName="GrupoBaseID")
     */
    private $TiposServicos;

    /*
     * ************************************************************
     * Construtor
     * ************************************************************
     */

    public function __construct()
    {
        $this->AtualizcoesBases = new ArrayCollection();
        $this->TiposServicos = new ArrayCollection();
    }

    /*
     * ************************************************************
     * Getter
     * ************************************************************
     */

    function getId(): int
    {
        return $this->Id;
    }

    function getGrupo(): string
    {
        return $this->Grupo;
    }

    function getAtualizcoesBases(): Collection
    {
        return $this->AtualizcoesBases;
    }
    function getTiposServicos(): Collection
    {
        return $this->TiposServicos;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    function setGrupo(string $Grupo)
    {
        $this->Grupo = $Grupo;
        return $this;
    }

    /*
     * ************************************************************
     * Add
     * ************************************************************
     */

    public function addAtualizacaoBase(AtualizacaoBase $AtualizacaoBase): self
    {
        $this->AtualizacaoBases->add($AtualizacaoBase);
        $AtualizacaoBase->setCliente($this);
        return $this;
    }

    public function addTipoServico(TipoServico $TipoServico): self
    {
        $this->TipoServicos->add($TipoServico);
        $TipoServico->setGrupoBase($this);
        return $this;
    }

}
