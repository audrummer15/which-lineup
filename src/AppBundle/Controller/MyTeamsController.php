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
        // replace this example code with whatever you need
        return $this->render('my-teams/my-teams.html.twig');
    }
}
