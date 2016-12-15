<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="PreMatchRepository")
 */
class PreMatch
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Municipality
     *
     * @ORM\ManyToOne(targetEntity="Municipality", inversedBy="preMatches")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $municipality;

    /**
     * @var ConnectionRequest
     *
     * @ORM\OneToOne(targetEntity="ConnectionRequest", inversedBy="fluentSpeakerPreMatch")
     */
    protected $fluentSpeakerConnectionRequest;

    /**
     * @var ConnectionRequest
     *
     * @ORM\OneToOne(targetEntity="ConnectionRequest", inversedBy="fluentSpeakerPreMatch")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $learnerConnectionRequest;

    /**
     * @var PreMatchIgnore[]
     *
     * @ORM\OneToMany(targetEntity="PreMatchIgnore", mappedBy="preMatch", cascade={"persist", "remove"})
     */
    protected $preMatchIgnores;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $verified= false;

    public function __construct()
    {
        $this->preMatchIgnores = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Municipality
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * @param Municipality $municipality
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * @return ConnectionRequest
     */
    public function getFluentSpeakerConnectionRequest()
    {
        return $this->fluentSpeakerConnectionRequest;
    }

    /**
     * @param ConnectionRequest $fluentSpeakerConnectionRequest
     */
    public function setFluentSpeakerConnectionRequest($fluentSpeakerConnectionRequest)
    {
        $this->fluentSpeakerConnectionRequest = $fluentSpeakerConnectionRequest;
    }

    /**
     * @return ConnectionRequest
     */
    public function getLearnerConnectionRequest()
    {
        return $this->learnerConnectionRequest;
    }

    /**
     * @param ConnectionRequest $learnerConnectionRequest
     */
    public function setLearnerConnectionRequest($learnerConnectionRequest)
    {
        $this->learnerConnectionRequest = $learnerConnectionRequest;
    }

    /**
     * @return PreMatchIgnore[]
     */
    public function getPreMatchIgnores()
    {
        return $this->preMatchIgnores;
    }

    /**
     * @param PreMatchIgnore[] $preMatchIgnores
     */
    public function setPreMatchIgnores($preMatchIgnores)
    {
        $this->preMatchIgnores = $preMatchIgnores;
    }

    /**
     * @param PreMatchIgnore $preMatchIgnore
     */
    public function addPreMatchIgnore(PreMatchIgnore $preMatchIgnore)
    {
        $this->preMatchIgnores->add($preMatchIgnore);
        $preMatchIgnore->setPreMatch($this);
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param boolean $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }
}
