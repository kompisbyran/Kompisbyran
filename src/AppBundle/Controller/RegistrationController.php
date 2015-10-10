<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
}
