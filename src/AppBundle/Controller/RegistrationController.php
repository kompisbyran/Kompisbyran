<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationController extends Controller
{
    /**
     * @Route("/register/confirmed", name="registration_confirmed")
     */
    public function confirmedAction()
    {
        return $this->redirectToRoute('settings');
    }

    /**
     * @Route("/login", name="login")
     * @Method("GET")
     */
    public function loginAction()
    {
        return $this->render('security/login.html.twig');
    }
}
