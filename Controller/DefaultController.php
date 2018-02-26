<?php

namespace ParcsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ParcsBundle:Default:index.html.twig');
    }
    public function ListAction()
    {
        return $this->render('@Parcs/Default/parc.html.twig');
    }
}
