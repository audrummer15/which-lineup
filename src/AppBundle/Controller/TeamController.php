<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Player;
use AppBundle\Form\Type\PlayerType;
use AppBundle\Entity\AtBat;

class TeamController extends Controller
{
    /**
     * @Route("/team/{id}", name="team")
     */
    public function teamAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $team = $em->getRepository('AppBundle:Team')->findOneBy(
            array(
                'user' => $user->getId(),
                'team_id' => $id
            )
        );

        $newPlayer = new Player();
        $playerForm = $this->createForm(PlayerType::class, $newPlayer);
        $playerForm->handleRequest($request);
        if ($playerForm->isSubmitted() && $playerForm->isValid()) {
            $team->addPlayer($newPlayer);
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('team', array('id'=> $id));
        }

        $atBats = $em->getRepository('AppBundle:AtBat')->findAllNonWalksForPlayer($team->getPlayers()[0]);

        return $this->render('team/team.html.twig', [
          'team' => $team,
          'playerForm' => $playerForm->createView(),
        ]);
    }

    /**
     * @Route("/team/add-single/{id}", name="add_single")
     */
    public function addSingleAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $player = $em->getRepository('AppBundle:Player')->findOneBy(
            array(
                'player_id' => $id
            )
        );

        return $this->addAtBat($player, AtBat::HIT_SINGLE);
    }

    /**
     * @Route("/team/add-double/{id}", name="add_double")
     */
    public function addDoubleAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $player = $em->getRepository('AppBundle:Player')->findOneBy(
            array(
                'player_id' => $id
            )
        );

        return $this->addAtBat($player, AtBat::HIT_DOUBLE);
    }

    /**
     * @Route("/team/add-triple/{id}", name="add_triple")
     */
    public function addTripleAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $player = $em->getRepository('AppBundle:Player')->findOneBy(
            array(
                'player_id' => $id
            )
        );

        return $this->addAtBat($player, AtBat::HIT_TRIPLE);
    }

    /**
     * @Route("/team/add-homerun/{id}", name="add_homerun")
     */
    public function addHomerunAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $player = $em->getRepository('AppBundle:Player')->findOneBy(
            array(
                'player_id' => $id
            )
        );

        return $this->addAtBat($player, AtBat::HIT_HOMERUN);
    }

    /**
     * @Route("/team/add-walk/{id}", name="add_walk")
     */
    public function addWalkAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $player = $em->getRepository('AppBundle:Player')->findOneBy(
            array(
                'player_id' => $id
            )
        );

        return $this->addAtBat($player, AtBat::HIT_WALK);
    }

    /**
     * @Route("/team/add-strikeout/{id}", name="add_strikeout")
     */
    public function addStrikeoutAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $player = $em->getRepository('AppBundle:Player')->findOneBy(
            array(
                'player_id' => $id
            )
        );

        return $this->addAtBat($player, AtBat::HIT_STRIKEOUT);
    }

    private function addAtBat($player, $hitValue)
    {
        $em = $this->getDoctrine()->getManager();
        $atBat = new AtBat();
        $atBat->setHit($hitValue);
        $atBat->setPlayer($player);
        $em->persist($atBat);
        $em->flush();

        $response = new JsonResponse();
        return $response->setData(array(
            'success' => true,
            'batting_average' => $player->getBattingAverage(),
            'slugging_average' => $player->getSluggingAverage(),
            'number_at_bats' => $em->
                createQuery('SELECT COUNT(a.atbat_id) FROM AppBundle:AtBat a WHERE a.player=:playerid')
                ->setParameter('playerid', $player->getPlayerId())
                ->getSingleScalarResult()
        ));
    }
}
