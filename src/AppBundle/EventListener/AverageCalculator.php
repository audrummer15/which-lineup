<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use AppBundle\Entity\AtBat;

class AverageCalculator implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $atBat = $args->getEntity();
        if (!$atBat instanceof AtBat) {
            return;
        }
        $em = $args->getEntityManager();

        $player = $atBat->getPlayer();
        $player->setBattingAverage($this->calculateBattingAverage($player, $atBat, $em));
        $player->setSluggingAverage($this->calculateSluggingAverage($player, $atBat, $em));
        $em->persist($player);
        $em->flush();
    }

    private function calculateBattingAverage($player, $atBat, $em)
    {
        $ba = $player->getBattingAverage();
        $atBats = count($em->getRepository('AppBundle:AtBat')->findAllNonWalksForPlayer($player));

        switch ($atBat->getHit())
        {
            case AtBat::HIT_SINGLE:
            case AtBat::HIT_DOUBLE:
            case AtBat::HIT_TRIPLE:
            case AtBat::HIT_HOMERUN:
                $ba = (($ba * $atBats) + 1) / ($atBats + 1);
                break;

            case AtBat::HIT_STRIKEOUT:
                $ba = ($ba * $atBats) / ($atBats + 1);
                break;
        }

        return number_format(round($ba, 3), 3);
    }

    private function calculateSluggingAverage($player, $atBat, $em)
    {
        $sa = $player->getSluggingAverage();
        $singles = count($em->getRepository('AppBundle:AtBat')->findBy(
            array(
                'player' => $player->getPlayerId(),
                'hit' => \AppBundle\Entity\AtBat::HIT_SINGLE
            )
        ));
        $doubles = count($em->getRepository('AppBundle:AtBat')->findBy(
            array(
                'player' => $player->getPlayerId(),
                'hit' => \AppBundle\Entity\AtBat::HIT_DOUBLE
            )
        ));
        $triples = count($em->getRepository('AppBundle:AtBat')->findBy(
            array(
                'player' => $player->getPlayerId(),
                'hit' => \AppBundle\Entity\AtBat::HIT_TRIPLE
            )
        ));
        $homeruns = count($em->getRepository('AppBundle:AtBat')->findBy(
            array(
                'player' => $player->getPlayerId(),
                'hit' => \AppBundle\Entity\AtBat::HIT_HOMERUN
            )
        ));
        $atBats = count($em->getRepository('AppBundle:AtBat')->findAllNonWalksForPlayer($player));

        switch ($atBat->getHit())
        {
            case AtBat::HIT_SINGLE:
                $singles++;
                break;
            case AtBat::HIT_DOUBLE:
                $doubles++;
                break;
            case AtBat::HIT_TRIPLE:
                $triples++;
                break;
            case AtBat::HIT_HOMERUN:
                $homeruns++;
                break;
        }


        $sa = ($singles + $doubles*2 + $triples*3 + $homeruns*4) / ($atBats + 1);
        return number_format(round($sa, 3), 3);
    }
}
