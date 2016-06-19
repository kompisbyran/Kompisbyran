<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\AdminUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("admin/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="admin_users")
     */
    public function indexAction()
    {
        $users = $this->getUserRepository()->findAllWithCategoryJoinAssoc();
        $categories = $this->getCategoryRepository()->findAll();

        $parameters = [
            'users' => $users,
            'categories' => $categories,
        ];

        return $this->render('admin/user/index.html.twig', $parameters);
    }

    /**
     * @Route("/{id}", name="admin_user", defaults={"id": null})
     * @Method({"POST", "GET"})
     */
    public function viewAction(Request $request, User $user)
    {
        $form = $this->createForm(
            new AdminUserType(),
            $user,
            [
                'manager'       => $this->getDoctrine()->getManager(),
                'locale'        => $request->getLocale()
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_start'));
        }

        $parameters = [
            'form' => $form->createView(),
            'user' => $user,
        ];

        return $this->render('admin/user/view.html.twig', $parameters);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", requirements={"id": "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction(User $user)
    {
        $user->setFirstName('x');
        $user->setLastName('x');
        $user->setEnabled(false);
        $user->setEmail('x' . $user->getId());
        $user->setUsername('x' . $user->getId());
        $user->setUsernameCanonical('x' . $user->getId());
        $user->setPassword('xxxxxxx');

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response();
    }

    protected function getUserRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
    }

    protected function getCategoryRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
    }
}
