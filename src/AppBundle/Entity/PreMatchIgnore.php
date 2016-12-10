<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PreMatchIgnore
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
     * @var PreMatch
     *
     * @ORM\ManyToOne(targetEntity="PreMatch", inversedBy="preMatchIgnores")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $preMatch;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $fluentSpeaker;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $learner;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PreMatch
     */
    public function getPreMatch()
    {
        return $this->preMatch;
    }

    /**
     * @param PreMatch $preMatch
     */
    public function setPreMatch($preMatch)
    {
        $this->preMatch = $preMatch;
    }

    /**
     * @return User
     */
    public function getFluentSpeaker()
    {
        return $this->fluentSpeaker;
    }

    /**
     * @param User $fluentSpeaker
     */
    public function setFluentSpeaker($fluentSpeaker)
    {
        $this->fluentSpeaker = $fluentSpeaker;
    }

    /**
     * @return User
     */
    public function getLearner()
    {
        return $this->learner;
    }

    /**
     * @param User $learner
     */
    public function setLearner($learner)
    {
        $this->learner = $learner;
    }
}
