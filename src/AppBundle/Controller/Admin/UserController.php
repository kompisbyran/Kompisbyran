<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\AdminUserType;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Manager\UserManager;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @Route("admin/users")
 */
class UserController extends Controller
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @InjectParams({
     *     "formFactory" = @Inject("form.factory")
     * })
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager, FormFactoryInterface $formFactory)
    {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
    }

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
        ];

        return $this->render('admin/user/view.html.twig', $parameters);
    }

    /**
     * @Route("/{id}", name="admin_ajax_edit", options={"expose"=true})
     */
    public function ajaxEditAction(Request $request, User $user)
    {
        $form   = $this->formFactory->create('admin_user', $user, [
            'manager'   => $this->getDoctrine()->getManager(),
            'locale'    => $request->getLocale()
        ]);

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView()
        ]);
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
