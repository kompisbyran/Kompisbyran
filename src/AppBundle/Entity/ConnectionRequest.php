<?php

namespace AppBundle\Entity;

use AppBundle\Enum\FriendTypes;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as KompisbyranAssert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @KompisbyranAssert\UserHasMusicCategories(groups="newConnectionRequest")
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
     * @Assert\Expression(
     *     "this.getType() != 'start' || this.getCity() == null",
     *     message="Du kan inte välja stad",
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
     *     "this.getType() == 'start' || this.getMunicipality() == null",
     *     message="Du kan inte välja kommun",
     *     groups={"Default"}
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $availableWeekday = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $availableWeekend = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $availableDay = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $availableEvening = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $extraPerson = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $extraPersonGender;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $extraPersonType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $matchingProfileRequestType;

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
     * @return boolean
     */
    public function isAvailableWeekday()
    {
        return $this->availableWeekday;
    }

    /**
     * @param boolean $availableWeekday
     */
    public function setAvailableWeekday($availableWeekday)
    {
        $this->availableWeekday = $availableWeekday;
    }

    /**
     * @return boolean
     */
    public function isAvailableWeekend()
    {
        return $this->availableWeekend;
    }

    /**
     * @param boolean $availableWeekend
     */
    public function setAvailableWeekend($availableWeekend)
    {
        $this->availableWeekend = $availableWeekend;
    }

    /**
     * @return boolean
     */
    public function isAvailableDay()
    {
        return $this->availableDay;
    }

    /**
     * @param boolean $availableDay
     */
    public function setAvailableDay($availableDay)
    {
        $this->availableDay = $availableDay;
    }

    /**
     * @return boolean
     */
    public function isAvailableEvening()
    {
        return $this->availableEvening;
    }

    /**
     * @param boolean $availableEvening
     */
    public function setAvailableEvening($availableEvening)
    {
        $this->availableEvening = $availableEvening;
    }

    /**
     * @return boolean
     */
    public function isExtraPerson()
    {
        return $this->extraPerson;
    }

    /**
     * @param boolean $extraPerson
     */
    public function setExtraPerson($extraPerson)
    {
        $this->extraPerson = $extraPerson;
    }

    /**
     * @return string
     */
    public function getExtraPersonGender()
    {
        return $this->extraPersonGender;
    }

    /**
     * @param string $extraPersonGender
     */
    public function setExtraPersonGender($extraPersonGender)
    {
        $this->extraPersonGender = $extraPersonGender;
    }

    /**
     * @return string
     */
    public function getExtraPersonType()
    {
        return $this->extraPersonType;
    }

    /**
     * @param string $extraPersonType
     */
    public function setExtraPersonType($extraPersonType)
    {
        $this->extraPersonType = $extraPersonType;
    }

    /**
     * @return boolean
     */
    public function wantSameGender()
    {
        return $this->wantSameGender;
    }

    /**
     * @param boolean $wantSameGender
     */
    public function setWantSameGender($wantSameGender)
    {
        $this->wantSameGender = $wantSameGender;
    }

    /**
     * @return boolean
     */
    public function wantSameAge()
    {
        return $this->wantSameAge;
    }

    /**
     * @param boolean $wantSameAge
     */
    public function setWantSameAge($wantSameAge)
    {
        $this->wantSameAge = $wantSameAge;
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
     * @Assert\Callback(groups={"newConnectionRequest", "registration", "Default"})
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (!$this->availableDay && !$this->availableEvening) {
            $context->buildViolation('Du måste välja minst ett alternativ')
                ->atPath('availableDay')
                ->addViolation();
        }

        if (!$this->availableWeekday && !$this->availableWeekend) {
            $context->buildViolation('Du måste välja minst ett alternativ')
                ->atPath('availableWeekday')
                ->addViolation();
        }
    }
}
