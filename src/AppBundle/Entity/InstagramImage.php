<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="InstagramImageRepository")
 * @ORM\Table(options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 */
class InstagramImage
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
    protected $instagramImageId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $profilePicture;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $caption;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $location;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $likesCount = 0;

    /**
     * @param string $instagramImageId
     * @param string $imageUrl
     * @param \DateTime $createdAt
     * @param string $link
     * @param string $username
     * @param string $profilePicture
     * @param string $caption
     * @param string|null $location
     * @param int $likesCount
     */
    public function __construct(
        $instagramImageId,
        \DateTime $createdAt,
        $imageUrl,
        $link,
        $username,
        $profilePicture,
        $caption,
        $location,
        $likesCount
    ) {
        $this->instagramImageId = $instagramImageId;
        $this->createdAt = $createdAt;
        $this->imageUrl = $imageUrl;
        $this->link = $link;
        $this->username = $username;
        $this->profilePicture = $profilePicture;
        $this->caption = $caption;
        $this->location = $location;
        $this->likesCount = $likesCount;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return string|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * @param int $likesCount
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getInstagramImageId()
    {
        return $this->instagramImageId;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
