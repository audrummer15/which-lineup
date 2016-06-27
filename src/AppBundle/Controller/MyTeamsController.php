<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('my-teams/my-teams.html.twig', [
          'teams' => $teams
        ]);
    }
}
