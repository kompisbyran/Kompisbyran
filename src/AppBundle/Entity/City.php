<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class City
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var ConnectionRequest[]
     *
     * @ORM\OneToMany(targetEntity="ConnectionRequest", mappedBy="city")
     */
    protected $connectionRequests;

    /**
     * @var Connection[]
     *
     * @ORM\OneToMany(targetEntity="Connection", mappedBy="city")
     */
    protected $connections;

    public function __construct()
    {
        $this->connectionRequests = new ArrayCollection();
        $this->connections = new ArrayCollection();
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \AppBundle\Entity\ConnectionRequest[] $connectionRequests
     */
    public function setConnectionRequests($connectionRequests)
    {
        $this->connectionRequests = $connectionRequests;
    }

    /**
     * @return \AppBundle\Entity\ConnectionRequest[]
     */
    public function getConnectionRequests()
    {
        return $this->connectionRequests;
    }
}
