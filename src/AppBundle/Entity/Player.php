<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Entity
 * @ORM\Table(name="player")
 */
class Player
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="player_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $player_id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="players")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="team_id")
     */
    private $team;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AtBat", mappedBy="player")
     */
    private $atbats;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->atbats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Player
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get playerId
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->player_id;
    }

    /**
     * Set team
     *
     * @param \AppBundle\Entity\Team $team
     *
     * @return Player
     */
    public function setTeam(\AppBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \AppBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Add atbat
     *
     * @param \AppBundle\Entity\AtBat $atbat
     *
     * @return Player
     */
    public function addAtbat(\AppBundle\Entity\AtBat $atbat)
    {
        $this->atbats[] = $atbat;

        return $this;
    }

    /**
     * Remove atbat
     *
     * @param \AppBundle\Entity\AtBat $atbat
     */
    public function removeAtbat(\AppBundle\Entity\AtBat $atbat)
    {
        $this->atbats->removeElement($atbat);
    }

    /**
     * Get atbats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAtbats()
    {
        return $this->atbats;
    }
}
