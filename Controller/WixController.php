<?php

namespace Wix\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Wix\BaseBundle\Configuration\Permission;

class WixController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return new Response('Hello Wix!');
    }
}
