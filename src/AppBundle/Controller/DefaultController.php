<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if (false === $this->get('security.authorization_checker')->isGranted('ROLE_COMPLETE_USER')) {
                return $this->redirect($this->generateUrl('settings'));
            }

            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm(
                new UserType(),
                $user,
                [
                    'validation_groups' => ['settings', 'newConnectionRequest'],
                    'manager' => $this->getDoctrine()->getManager(),
                    'locale' => $request->getLocale(),
                    'add_connection_request' => true,
                    'translator' => $this->get('translator'),
                    'newly_arrived_date' => $this->get('newly_arrived_date'),
                ]
            );

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $connectionRequest = $user->getNewConnectionRequest();
                    $em->persist($user);
                    $em->persist($connectionRequest);
                    $em->flush();

                    $this->addFlash('info', $this->get('translator')->trans('connection_request.created.flash'));

                    return $this->redirect($this->generateUrl('homepage'));
                } else {
                    $this->addFlash('error', $this->get('translator')
                        ->trans('connection_request.validation_failed.flash'));
                }
            }

            $newUser = false;
            foreach ($this->container->get('session')->getFlashBag()->get('data') as $message) {
                if ($message == 'newUser') {
                    $newUser = true;
                }
            }

            $parameters = [
                'form' => $form->createView(),
                'connectionRequest' =>  $this->get('connection_request_repository')->findOneOpenByUser($user),
                'startMunicipalities' => $this->get('municipality_repository')->findAllActiveStartMunicipalities(),
                'matchFamilyMunicipalities' => $this->get('municipality_repository')->findAllMatchFamilyMunicipalities(),
                'newUser' => $newUser,
            ];
        } else {
            $parameters = [];
        }

        return $this->render('default/index.html.twig', $parameters);
    }

    /**
     * @Route("/danderyd", name="danderyd")
     */
    public function danderydAction(Request $request)
    {
        $email = null;
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $validator = $this->get('validator')->validate($email, [new Email(), new NotBlank()]);
            if ($validator->count() > 0) {
                $this->addFlash('error', $validator->get(0)->getMessage());
            } else {
                $body = sprintf('Epostadressen "%s" vill bli kontaktad när Kompisbyråns samarbete med Daneryd kommun startar.', $email);
                $this->get('app.user_mailer')->sendEmailMessage(null, $body, 'Danderyd', 'clara@kompisbyran.se');
                $this->addFlash('info', $this->get('translator')->trans('notice', [], 'danderyd'));

                return $this->redirectToRoute('danderyd');
            }
        }

        $parameters = [
            'email' => $email,
        ];

        return $this->render('default/danderyd.html.twig', $parameters);

    }
}
