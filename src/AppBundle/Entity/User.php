<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="fos_user")
*/
class User extends BaseUser
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @param mixed $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
