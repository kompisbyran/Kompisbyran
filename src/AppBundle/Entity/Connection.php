<?php

namespace AppBundle\Entity;

use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\MeetingTypes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator\Constraints as KompisbyranAssert;

/**
 * @ORM\Entity(repositoryClass="ConnectionRepository")
 */
class Connection
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fluentSpeakerConnections")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $fluentSpeaker;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="learnerConnections")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $learner;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City", inversedBy="connections")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $city;

    /**
     * @var Municipality
     *
     * @ORM\ManyToOne(targetEntity="Municipality", inversedBy="connections")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $municipality;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $learnerComment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fluentSpeakerComment;

    /**
     * @var string
     *
     * @KompisbyranAssert\ValidMeetingStatus
     *
     * @ORM\Column(type="string")
     */
    protected $learnerMeetingStatus;

    /**
     * @var string
     *
     * @KompisbyranAssert\ValidMeetingStatus
     *
     * @ORM\Column(type="string")
     */
    protected $fluentSpeakerMeetingStatus;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $learnerMeetingStatusEmailsCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $fluentSpeakerMeetingStatusEmailsCount = 0;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdConnections")
     * @ORM\JoinColumn(nullable=true, name="created_by")
     */
    protected $createdBy;

    /**
     * @var ConnectionComment[]
     *
     * @ORM\OneToMany(targetEntity="ConnectionComment", mappedBy="connection")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $comments;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $learnerConnectionRequestCreatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $fluentSpeakerConnectionRequestCreatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $newlyArrived = false;

    public function __construct(User $user = null)
    {
        $this->createdAt = new \DateTime();
        $this->createdBy = $user;
        $this->comments = new ArrayCollection();
        $this->type = FriendTypes::FRIEND;
        $this->fluentSpeakerMeetingStatus = MeetingTypes::UNKNOWN;
        $this->learnerMeetingStatus = MeetingTypes::UNKNOWN;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * @param \AppBundle\Entity\User $fluentSpeaker
     */
    public function setFluentSpeaker($fluentSpeaker)
    {
        $this->fluentSpeaker = $fluentSpeaker;
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getFluentSpeaker()
    {
        return $this->fluentSpeaker;
    }

    /**
     * @param \AppBundle\Entity\User $learner
     */
    public function setLearner($learner)
    {
        $this->learner = $learner;
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getLearner()
    {
        return $this->learner;
    }

    /**
     * @param string $fluentSpeakerComment
     */
    public function setFluentSpeakerComment($fluentSpeakerComment)
    {
        $this->fluentSpeakerComment = $fluentSpeakerComment;
    }

    /**
     * @return string
     */
    public function getFluentSpeakerComment()
    {
        return $this->fluentSpeakerComment;
    }

    /**
     * @param string $learnerComment
     */
    public function setLearnerComment($learnerComment)
    {
        $this->learnerComment = $learnerComment;
    }

    /**
     * @return string
     */
    public function getLearnerComment()
    {
        return $this->learnerComment;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setCreatedBy(User $user)
    {
        $this->createdBy = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return ConnectionComment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return \DateTime
     */
    public function getLearnerConnectionRequestCreatedAt()
    {
        return $this->learnerConnectionRequestCreatedAt;
    }

    /**
     * @param \DateTime $learnerConnectionRequestCreatedAt
     */
    public function setLearnerConnectionRequestCreatedAt($learnerConnectionRequestCreatedAt)
    {
        $this->learnerConnectionRequestCreatedAt = $learnerConnectionRequestCreatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getFluentSpeakerConnectionRequestCreatedAt()
    {
        return $this->fluentSpeakerConnectionRequestCreatedAt;
    }

    /**
     * @param \DateTime $fluentSpeakerConnectionRequestCreatedAt
     */
    public function setFluentSpeakerConnectionRequestCreatedAt($fluentSpeakerConnectionRequestCreatedAt)
    {
        $this->fluentSpeakerConnectionRequestCreatedAt = $fluentSpeakerConnectionRequestCreatedAt;
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
     * @return bool
     */
    public function isNewlyArrived()
    {
        return $this->newlyArrived;
    }

    /**
     * @param bool $newlyArrived
     */
    public function setNewlyArrived($newlyArrived)
    {
        $this->newlyArrived = $newlyArrived;
    }

    /**
     * @return string
     */
    public function getLearnerMeetingStatus()
    {
        return $this->learnerMeetingStatus;
    }

    /**
     * @param string $learnerMeetingStatus
     */
    public function setLearnerMeetingStatus($learnerMeetingStatus)
    {
        $this->learnerMeetingStatus = $learnerMeetingStatus;
    }

    /**
     * @return string
     */
    public function getFluentSpeakerMeetingStatus()
    {
        return $this->fluentSpeakerMeetingStatus;
    }

    /**
     * @param string $fluentSpeakerMeetingStatus
     */
    public function setFluentSpeakerMeetingStatus($fluentSpeakerMeetingStatus)
    {
        $this->fluentSpeakerMeetingStatus = $fluentSpeakerMeetingStatus;
    }

    /**
     * @return int
     */
    public function getLearnerMeetingStatusEmailsCount()
    {
        return $this->learnerMeetingStatusEmailsCount;
    }

    /**
     * @param int $learnerMeetingStatusEmailsCount
     */
    public function setLearnerMeetingStatusEmailsCount($learnerMeetingStatusEmailsCount)
    {
        $this->learnerMeetingStatusEmailsCount = $learnerMeetingStatusEmailsCount;
    }

    /**
     * @return int
     */
    public function getFluentSpeakerMeetingStatusEmailsCount()
    {
        return $this->fluentSpeakerMeetingStatusEmailsCount;
    }

    /**
     * @param int $fluentSpeakerMeetingStatusEmailsCount
     */
    public function setFluentSpeakerMeetingStatusEmailsCount($fluentSpeakerMeetingStatusEmailsCount)
    {
        $this->fluentSpeakerMeetingStatusEmailsCount = $fluentSpeakerMeetingStatusEmailsCount;
    }
}
