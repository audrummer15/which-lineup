<?php

namespace AppBundle\Repository;

/**
 * AtBatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AtBatRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllNonWalksForPlayer($player)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a
                FROM AppBundle:AtBat a
                WHERE a.hit != :walk AND a.player=:playerid'
            )
            ->setParameter('walk', \AppBundle\Entity\AtBat::HIT_WALK)
            ->setParameter('playerid', $player->getPlayerId())
            ->getResult();
    }
}
