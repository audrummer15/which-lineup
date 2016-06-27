<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Team;
use AppBundle\Form\Type\TeamType;

class MyTeamsController extends Controller
{
    /**
     * @Route("/my-teams", name="my_teams")
     */
    public function myTeamsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $teams = $em->getRepository('AppBundle:Team')->findBy(
            array(
                'user' => $user->getId()
            )
        );

        $newTeam = new Team();
        $teamForm = $this->createForm(TeamType::class, $newTeam);
        $teamForm->handleRequest($request);
        if ($teamForm->isSubmitted() && $teamForm->isValid()) {
            $user->addTeam($newTeam);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('my_teams');
        }

        return $this->render('my-teams/my-teams.html.twig', [
          'teams' => $teams,
          'teamForm' => $teamForm->createView()
        ]);
    }
}
