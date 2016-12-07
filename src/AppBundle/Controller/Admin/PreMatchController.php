<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Municipality;
use AppBundle\Security\Authorization\Voter\MunicipalityVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class PreMatchController extends Controller
{
    /**
     * @Route("/pre-match/{id}", name="admin_pre_match")
     * @Method("GET")
     */
    public function indexAction(Municipality $municipality)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $municipality);

        $preMatches =

        return $this->render('admin/preMatch/index.html.twig');
    }
}
