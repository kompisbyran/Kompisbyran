<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MunicipalityRepository")
 */
class Municipality
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $meetingPlace;

    /**
     * @var User[]
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="municipality")
     */
    protected $users;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="adminMunicipalities")
     */
    protected $adminUsers;

    /**
     * @var PreMatch[]
     *
     * @ORM\OneToMany(targetEntity="PreMatch", mappedBy="municipality")
     */
    protected $preMatches;

    /**
     * @var ConnectionRequest[]
     *
     * @ORM\OneToMany(targetEntity="ConnectionRequest", mappedBy="municipality")
     */
    protected $connectionRequests;

    /**
     * @var Connection[]
     *
     * @ORM\OneToMany(targetEntity="Connection", mappedBy="municipality")
     */
    protected $connections;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $startMunicipality = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $activeStartMunicipality = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $matchFamily = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $senderEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $followUpEmailTemplate;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->adminUsers = new ArrayCollection();
        $this->preMatches = new ArrayCollection();
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
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User[] $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return User[]
     */
    public function getAdminUsers()
    {
        return $this->adminUsers;
    }

    /**
     * @param User[] $adminUsers
     */
    public function setAdminUsers($adminUsers)
    {
        $this->adminUsers = $adminUsers;
    }

    /**
     * @param User $adminUser
     */
    public function addAdminUser(User $adminUser)
    {
        $this->adminUsers->add($adminUser);
    }

    /**
     * @return PreMatch[]
     */
    public function getPreMatches()
    {
        return $this->preMatches;
    }

    /**
     * @param PreMatch[] $preMatches
     */
    public function setPreMatches($preMatches)
    {
        $this->preMatches = $preMatches;
    }

    /**
     * @return boolean
     */
    public function isStartMunicipality()
    {
        return $this->startMunicipality;
    }

    /**
     * @param boolean $startMunicipality
     */
    public function setStartMunicipality($startMunicipality)
    {
        $this->startMunicipality = $startMunicipality;
    }

    /**
     * @return ConnectionRequest[]
     */
    public function getConnectionRequests()
    {
        return $this->connectionRequests;
    }

    /**
     * @param ConnectionRequest[] $connectionRequests
     */
    public function setConnectionRequests($connectionRequests)
    {
        $this->connectionRequests = $connectionRequests;
    }

    /**
     * @return string
     */
    public function getMeetingPlace()
    {
        return $this->meetingPlace;
    }

    /**
     * @param string $meetingPlace
     */
    public function setMeetingPlace($meetingPlace)
    {
        $this->meetingPlace = $meetingPlace;
    }

    /**
     * @return bool
     */
    public function isActiveStartMunicipality()
    {
        return $this->activeStartMunicipality;
    }

    /**
     * @param bool $activeStartMunicipality
     */
    public function setActiveStartMunicipality($activeStartMunicipality)
    {
        $this->activeStartMunicipality = $activeStartMunicipality;
    }

    /**
     * @return bool
     */
    public function isMatchFamily()
    {
        return $this->matchFamily;
    }

    /**
     * @param bool $matchFamily
     */
    public function setMatchFamily($matchFamily)
    {
        $this->matchFamily = $matchFamily;
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return string
     */
    public function getFollowUpEmailTemplate()
    {
        return $this->followUpEmailTemplate;
    }

    /**
     * @param string $followUpEmailTemplate
     */
    public function setFollowUpEmailTemplate($followUpEmailTemplate)
    {
        $this->followUpEmailTemplate = $followUpEmailTemplate;
    }
}
