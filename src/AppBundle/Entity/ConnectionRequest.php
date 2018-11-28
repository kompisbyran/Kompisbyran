<?php

namespace AppBundle\Entity;

use AppBundle\Enum\FriendTypes;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as KompisbyranAssert;

/**
 * @ORM\Entity(repositoryClass="ConnectionRequestRepository")
 */
class ConnectionRequest
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="connectionRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var City
     *
     * Expression uses user type since type is not copied to connection request on time of validation
     * @Assert\Expression(
     *     "this.getUser().getType() == 'start' || this.getCity() != null",
     *     message="Du måste välja stad",
     *     groups={"newConnectionRequest", "registration"}
     * )
     *
     * @Assert\Expression(
     *     "this.getType() == 'start' || this.getCity() != null",
     *     message="Du måste välja stad",
     *     groups={"Default"}
     * )
     *
     * @ORM\ManyToOne(targetEntity="City", inversedBy="connectionRequests")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $city;

    /**
     * @var Municipality
     *
     * Expression uses user type since type is not copied to connection request on time of validation
     * @Assert\Expression(
     *     "this.getUser().getType() != 'start' || this.getMunicipality() != null",
     *     message="Du måste välja kommun",
     *     groups={"newConnectionRequest", "registration"}
     * )
     *
     * @Assert\Expression(
     *     "this.getType() != 'start' || this.getMunicipality() != null",
     *     message="Du måste välja kommun",
     *     groups={"Default"}
     * )
     *
     * @Assert\Expression(
     *     "this.getUser().getType() != 'start' || this.getMunicipality() == this.getUser().getMunicipality()",
     *     message="Du måste välja den kommun du bor i.",
     *     groups={"newConnectionRequest", "registration"}
     * )
     *
     * @ORM\ManyToOne(targetEntity="Municipality", inversedBy="connectionRequests")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $municipality;

    /**
     * @var PreMatch
     *
     * @ORM\OneToOne(targetEntity="PreMatch", mappedBy="fluentSpeakerConnectionRequest", cascade={"remove"})
     */
    protected $fluentSpeakerPreMatch;

    /**
     * @var PreMatch
     *
     * @ORM\OneToOne(targetEntity="PreMatch", mappedBy="learnerConnectionRequest", cascade={"remove"})
     */
    protected $learnerPreMatch;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $wantToLearn = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $sortOrder;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $disqualified = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $disqualifiedComment;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $pending = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $inspected = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $matchingProfileRequestType;

    /**
     * @var Connection|null
     *
     * @ORM\OneToOne(targetEntity="Connection", mappedBy="fluentSpeakerConnectionRequest")
     */
    protected $fluentSpeakerConnection;

    /**
     * @var Connection|null
     *
     * @ORM\OneToOne(targetEntity="Connection", mappedBy="learnerConnectionRequest")
     */
    protected $learnerConnection;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $matchFamily = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->sortOrder = 0;
        $this->type = FriendTypes::FRIEND;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \AppBundle\Entity\City $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return \AppBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param \AppBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param boolean $wantToLearn
     */
    public function setWantToLearn($wantToLearn)
    {
        $this->wantToLearn = $wantToLearn;
    }

    /**
     * @return boolean
     */
    public function getWantToLearn()
    {
        return $this->wantToLearn;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Set disqualified
     *
     * @param boolean $disqualified
     *
     * @return ConnectionRequest
     */
    public function setDisqualified($disqualified)
    {
        $this->disqualified = $disqualified;

        return $this;
    }

    /**
     * Get disqualified
     *
     * @return boolean
     */
    public function getDisqualified()
    {
        return $this->disqualified;
    }

    /**
     * Set disqualifiedComment
     *
     * @param string $disqualifiedComment
     *
     * @return ConnectionRequest
     */
    public function setDisqualifiedComment($disqualifiedComment)
    {
        $this->disqualifiedComment = $disqualifiedComment;

        return $this;
    }

    /**
     * Get disqualifiedComment
     *
     * @return string
     */
    public function getDisqualifiedComment()
    {
        return $this->disqualifiedComment;
    }

    /**
     * @param boolean $pending
     */
    public function setPending($pending)
    {
        $this->pending = $pending;
    }

    /**
     * @return boolean
     */
    public function getPending()
    {
        return $this->pending;
    }

    /**
     * @param boolean $inspected
     */
    public function setInspected($inspected)
    {
        $this->inspected = $inspected;
    }

    /**
     * @return boolean
     */
    public function getInspected()
    {
        return $this->inspected;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return PreMatch
     */
    public function getFluentSpeakerPreMatch()
    {
        return $this->fluentSpeakerPreMatch;
    }

    /**
     * @param PreMatch $fluentSpeakerPreMatch
     */
    public function setFluentSpeakerPreMatch($fluentSpeakerPreMatch)
    {
        $this->fluentSpeakerPreMatch = $fluentSpeakerPreMatch;
    }

    /**
     * @return PreMatch
     */
    public function getLearnerPreMatch()
    {
        return $this->learnerPreMatch;
    }

    /**
     * @param PreMatch $learnerPreMatch
     */
    public function setLearnerPreMatch($learnerPreMatch)
    {
        $this->learnerPreMatch = $learnerPreMatch;
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
     * @return string
     */
    public function getMatchingProfileRequestType()
    {
        return $this->matchingProfileRequestType;
    }

    /**
     * @param string $matchingProfileRequestType
     */
    public function setMatchingProfileRequestType($matchingProfileRequestType)
    {
        $this->matchingProfileRequestType = $matchingProfileRequestType;
    }

    /**
     * @return Connection|null
     */
    public function getConnection()
    {
        if ($this->wantToLearn) {
            return $this->learnerConnection;
        } else {
            return $this->fluentSpeakerConnection;
        }
    }

    /**
     * @param Connection|null $connection
     */
    public function setConnection($connection)
    {
        if ($this->wantToLearn) {
            $this->learnerConnection = $connection;
        } else {
            $this->fluentSpeakerConnection = $connection;
        }
    }

    /**
     * @return bool
     */
    public function isClonable()
    {
        if (!$this->getConnection()) {
            return false;
        }

        if ($this->getUser()->hasOpenConnectionRequest()) {
            return false;
        }

        return true;
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
}
