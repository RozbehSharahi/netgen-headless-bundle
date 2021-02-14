<?php

namespace Rs\NetgenHeadless\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    const SUCCESS_MESSAGE = 'NetgenHeadlessBundles installed';

    /**
     * @Route("/", name="netgen-headless-home")
     */
    public function index()
    {
        return new Response(self::SUCCESS_MESSAGE);
    }
}
