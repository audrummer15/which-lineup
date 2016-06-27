<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AtBat
 *
 * @ORM\Table(name="at_bat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AtBatRepository")
 */
class AtBat
{
    /**
     * @var int
     *
     * @ORM\Column(name="atbat_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $atbat_id;

    /**
     * @var int
     *
     * @ORM\Column(name="hit", type="smallint")
     */
    private $hit;

    /**
     * @var int
     *
     * @ORM\Column(name="rbi", type="smallint")
     */
    private $rbi;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="atbats")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="player_id")
     */
    private $player;


    /**
     * Set hit
     *
     * @param integer $hit
     *
     * @return AtBat
     */
    public function setHit($hit)
    {
        $this->hit = $hit;

        return $this;
    }

    /**
     * Get hit
     *
     * @return int
     */
    public function getHit()
    {
        return $this->hit;
    }

    /**
     * Set rbi
     *
     * @param integer $rbi
     *
     * @return AtBat
     */
    public function setRbi($rbi)
    {
        $this->rbi = $rbi;

        return $this;
    }

    /**
     * Get rbi
     *
     * @return int
     */
    public function getRbi()
    {
        return $this->rbi;
    }

    /**
     * Get atbatId
     *
     * @return integer
     */
    public function getAtbatId()
    {
        return $this->atbat_id;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return AtBat
     */
    public function setPlayer(\AppBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
