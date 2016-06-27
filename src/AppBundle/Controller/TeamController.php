<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Player;
use AppBundle\Form\Type\PlayerType;

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

        return $this->render('team/team.html.twig', [
          'team' => $team,
          'playerForm' => $playerForm->createView(),
        ]);
    }
}
