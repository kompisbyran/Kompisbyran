<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->adminUsers = new ArrayCollection();
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
}
