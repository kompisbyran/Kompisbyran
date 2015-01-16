<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Form\ConnectionRequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            /** @var \AppBundle\Entity\User $user */
            $user = $this->getUser();
            $connectionRequest = new ConnectionRequest();
            $connectionRequest->setUser($user);
            $connectionRequest->setWantToLearn($user->getWantToLearn());
            $form = $this->createForm(new ConnectionRequestType(), $connectionRequest);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($connectionRequest);
                $em->flush();

                return $this->redirect($this->generateUrl('homepage'));
            }

            $parameters = [
                'form' => $form->createView(),
            ];
        } else {
            $parameters = [];
        }

        return $this->render('default/index.html.twig', $parameters);
    }
}
