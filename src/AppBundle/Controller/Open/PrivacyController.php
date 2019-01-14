<?php

namespace AppBundle\Controller\Open;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PrivacyController extends Controller
{
    /**
     * @Route("/public/privacy/{uuid}", name="public_privacy")
     */
    public function indexAction($uuid, Request $request)
    {
        /** @var User $user */
        $user = $this->get('user_repository')->findOneBy(['uuid' => $uuid]);
        if (!$user) {
            throw $this->createNotFoundException(
                sprintf('User not found for %s.', $uuid)
            );
        }

        $confirmed = false;
        if ($request->isMethod('POST')) {
            $user->setConfirmedKeepDataAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            $confirmed = true;
        }

        $parameters = [
            'confirmed' => $confirmed,
            'uuid' => $uuid,
        ];

        return $this->render('open/privacy.html.twig', $parameters);
    }
}
