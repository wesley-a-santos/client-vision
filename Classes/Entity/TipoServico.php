<?php

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
 * Description of TipoServico
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br at http://www.cemob.sp.caixa/>
 * @Entity(repositoryClass="Classes\Repository\TipoServicoRepository")
 * @Table(name="TiposServicos")
 */
class TipoServico {

    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="smallint", name="TipoServicoID", unique=true, nullable=false)
     */
    private $Id;

    /**
     * @Column(type="string", length=50, name="Tipo", unique=false, nullable=false)
     */
    private $Tipo;

    /**
     * @Column(type="string", length=150, name="Descricao", unique=false, nullable=true)
     */
    private $Descricao;

    /**
     * @OneToMany(targetEntity="Demanda", mappedBy="TipoServico", cascade={"persist"}, orphanRemoval=true)
     * @JoinColumn(name="TipoServicoID", referencedColumnName="TipoServicoID")
     */
    private $Demandas;

    /**
     * @ManyToOne(targetEntity="GrupoBase", inversedBy="TiposServicos", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="GrupoBaseID", referencedColumnName="GrupoBaseID")
     */
    private $GrupoBase;

    /*
     * ************************************************************
     * Construct
     * ************************************************************
     */

    public function __construct()
    {
        $this->Demandas = new ArrayCollection();
        $this->AtualizcoesBases = new ArrayCollection();
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

    public function getTipo(): string
    {
        return $this->Tipo;
    }

    public function getDemandas(): Collection
    {
        return $this->Demandas;
    }

    public function getGrupoBase(): GrupoBase
    {
        return $this->GrupoBase;
    }

    function getDescricao(): ?string
    {
        return $this->Descricao;
    }

    /*
     * ************************************************************
     * Setter
     * ************************************************************
     */

    public function setTipo(string $Tipo): self
    {
        $this->Tipo = $Tipo;
        return $this;
    }

    public function setGrupoBase(GrupoBase $GrupoBase): self
    {
        $this->GrupoBase = $GrupoBase;
        return $this;
    }

    function setDescricao(string $Descricao): self
    {
        $this->Descricao = $Descricao;
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
        $Demanda->setTipoServico($this);
        return $this;
    }

}
