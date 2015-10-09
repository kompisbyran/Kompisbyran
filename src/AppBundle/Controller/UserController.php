<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="settings")
     */
    public function settingsAction(Request $request)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $this->getUser();
        $form = $this->createForm(new UserType(), $user, ['validation_groups' => ['settings']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->addRole('ROLE_COMPLETE_USER');
            $em->persist($user);
            $em->flush();

            $this->get('security.context')->getToken()->setAuthenticated(false);

            return $this->redirect($this->generateUrl('homepage'));
        }

        $parameters = [
            'form' => $form->createView(),
        ];

        return $this->render('user/settings.html.twig', $parameters);
    }
}
