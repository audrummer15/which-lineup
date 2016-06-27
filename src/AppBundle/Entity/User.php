<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
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
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Team", mappedBy="user", cascade={"persist", "remove"})
     */
    private $teams;


    public function __construct()
    {
        parent::__construct();

        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->username = $email;

        return $this;
    }

    public function setEmailCanonical($emailCanonical)
    {
        parent::setEmailCanonical($emailCanonical);
        $this->usernameCanonical = $emailCanonical;

        return $this;
    }

    /**
     * Add team
     *
     * @param \AppBundle\Entity\Team $team
     *
     * @return User
     */
    public function addTeam(\AppBundle\Entity\Team $team)
    {
        $this->teams[] = $team;
        $team->setUser($this);
        
        return $this;
    }

    /**
     * Remove team
     *
     * @param \AppBundle\Entity\Team $team
     */
    public function removeTeam(\AppBundle\Entity\Team $team)
    {
        $this->teams->removeElement($team);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
