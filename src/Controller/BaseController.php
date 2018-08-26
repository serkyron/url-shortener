<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BaseController extends AbstractController
{
    /**
     * Corresponds to application home page
     *
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home.html.twig');
    }

    /**
     * API method used to create a short URL
     *
     * @Route("/api/shorten/url", name="shorten")
     * @Method({"POST"})
     */
    public function shorten()
    {
        
    }
}